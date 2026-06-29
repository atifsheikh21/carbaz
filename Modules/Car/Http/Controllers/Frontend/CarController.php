<?php

namespace Modules\Car\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Country\Entities\Country;
use Modules\City\Entities\City;
use Illuminate\Support\Facades\Schema;
use Modules\Brand\Entities\Brand;
use Modules\Car\Entities\Car;
use Modules\Car\Entities\CarGallery;
use Modules\Feature\Entities\Feature;
use Modules\Language\Entities\Language;
use App\Models\IndividualAdPayment;
use App\Models\StripePayment;
use Modules\Car\Entities\CarTranslation;
use Modules\Car\Http\Requests\CarRequest;
use Illuminate\Contracts\Support\Renderable;
use Modules\GeneralSetting\Entities\Setting;
use Modules\Subscription\Entities\SubscriptionHistory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::guard('web')->user();

        $status = $request->get('status', 'all');

        $today = date('Y-m-d');

        $baseQuery = Car::where('agent_id', $user->id);

        $totalCount = (clone $baseQuery)->count();

        $activeCount = (clone $baseQuery)
            ->where('approved_by_admin', 'approved')
            ->where('status', 'enable')
            ->where(function ($q) use ($today) {
                $q->whereNull('expired_date')->orWhere('expired_date', '>=', $today);
            })
            ->count();

        $inactiveCount = (clone $baseQuery)
            ->where(function ($q) use ($today) {
                $q->where('approved_by_admin', '!=', 'approved')
                    ->orWhere('status', '!=', 'enable')
                    ->orWhere(function ($q2) use ($today) {
                        $q2->whereNotNull('expired_date')->where('expired_date', '<', $today);
                    });
            })
            ->count();

        $carsQuery = clone $baseQuery;
        if ($status === 'active') {
            $carsQuery
                ->where('approved_by_admin', 'approved')
                ->where('status', 'enable')
                ->where(function ($q) use ($today) {
                    $q->whereNull('expired_date')->orWhere('expired_date', '>=', $today);
                });
        } elseif ($status === 'inactive') {
            $carsQuery->where(function ($q) use ($today) {
                $q->where('approved_by_admin', '!=', 'approved')
                    ->orWhere('status', '!=', 'enable')
                    ->orWhere(function ($q2) use ($today) {
                        $q2->whereNotNull('expired_date')->where('expired_date', '<', $today);
                    });
            });
        }

        $cars = $carsQuery->latest()->paginate(15)->appends($request->query());

        return view('car::frontend.index', [
            'cars' => $cars,
            'status' => $status,
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
            'today' => $today,
        ]);
    }

    public function toggleStatus(Request $request, Car $car)
    {
        $user = Auth::guard('web')->user();

        if (!$user || (int) $car->agent_id !== (int) $user->id) {
            abort(403);
        }

        $today = date('Y-m-d');
        $isExpired = !empty($car->expired_date) && $car->expired_date < $today;
        $isActive = $car->approved_by_admin === 'approved'
            && $car->status === 'enable'
            && (!$car->expired_date || $car->expired_date >= $today);

        if ($isActive) {
            $car->status = 'disable';
            $car->save();

            $notification = trans('translate.Updated Successfully');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->back()->with($notification);
        }

        if ($user->is_dealer) {
            $setting = Setting::first();
            $feeFreeModeEnabled = $setting && $setting->fee_free_mode == 'enable';

            if ($feeFreeModeEnabled) {
                $car->expired_date = date('Y-m-d', strtotime('+30 days'));
                $car->status = 'enable';
                $car->save();

                $notification = $isExpired ? 'Your ad has been re-activated successfully.' : trans('translate.Updated Successfully');
                $notification = array('messege' => $notification, 'alert-type' => 'success');
                return redirect()->back()->with($notification);
            }

            $activePlan = SubscriptionHistory::where('user_id', $user->id)
                ->where('status', 'active')
                ->latest()
                ->first();

            if (!$activePlan) {
                $notification = trans('translate.Please enroll first');
                $notification = array('messege' => $notification, 'alert-type' => 'error');
                return redirect()->route('pricing-plan')->with($notification);
            }

            if ($activePlan->expiration_date !== 'lifetime' && $today > $activePlan->expiration_date) {
                $notification = trans('translate.Your plan is expired, please renew or re-order');
                $notification = array('messege' => $notification, 'alert-type' => 'error');
                return redirect()->route('pricing-plan')->with($notification);
            }

            $maxCar = (int) $activePlan->max_car;
            $currentActiveCount = Car::where('agent_id', $user->id)
                ->where('id', '!=', $car->id)
                ->where('approved_by_admin', 'approved')
                ->where('status', 'enable')
                ->where(function ($q) use ($today) {
                    $q->whereNull('expired_date')->orWhere('expired_date', '>=', $today);
                })
                ->count();

            if ($maxCar > 0 && $currentActiveCount >= $maxCar) {
                $notification = trans('translate.Your car limitation has exceeded');
                $notification = array('messege' => $notification, 'alert-type' => 'error');
                return redirect()->back()->with($notification);
            }

            if ($activePlan->expiration_date === 'lifetime') {
                $car->expired_date = date('Y-m-d', strtotime('+30 days'));
            } else {
                $perAdExpiry = date('Y-m-d', strtotime('+30 days'));
                $car->expired_date = $activePlan->expiration_date < $perAdExpiry ? $activePlan->expiration_date : $perAdExpiry;
            }
        } else {
            $setting = Setting::first();
            $feeFreeModeEnabled = $setting && $setting->fee_free_mode == 'enable';

            $pendingPaymentQuery = IndividualAdPayment::where('user_id', $user->id)
                ->where('status', 'success')
                ->whereNull('consumed_at');

            if (!$feeFreeModeEnabled) {
                $pendingPaymentQuery->where('payment_method', '!=', 'Free');
            }

            $pendingPayment = $pendingPaymentQuery->orderBy('id', 'asc')->first();

            if (!$pendingPayment) {
                if ($feeFreeModeEnabled) {
                    $pendingPayment = IndividualAdPayment::create([
                        'user_id' => $user->id,
                        'car_id' => null,
                        'amount' => 0,
                        'currency' => 'EUR',
                        'payment_method' => 'Free',
                        'status' => 'success',
                        'transaction_id' => 'fee_free_mode_reactivate',
                        'consumed_at' => null,
                    ]);
                } else {
                    $notification = trans('translate.Please complete payment to post your ad');
                    $notification = array('messege' => $notification, 'alert-type' => 'error');
                    return redirect()->back()->with($notification);
                }
            }

            $pendingPayment->car_id = $car->id;
            $pendingPayment->consumed_at = now();
            $pendingPayment->save();

            $car->is_paid = 1;
            $car->expired_date = date('Y-m-d', strtotime('+30 days'));
        }

        $car->status = 'enable';
        $car->save();

        $notification = $isExpired ? 'Your ad has been re-activated successfully.' : trans('translate.Updated Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function select_car_purpose()
    {
        $stripe = Schema::hasTable('stripe_payments') ? StripePayment::first() : null;
        $setting = Setting::first();
        return view('car::frontend.select_car_purpose', compact('stripe', 'setting'));
    }



    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {

        $ireland = Country::where('code', 'IE')->orWhere('name', 'Ireland')->first();

        $countries = $ireland ? collect([$ireland]) : collect();

        $user = Auth::guard('web')->user();

        if ($user?->is_dealer && !(bool) $user?->is_vehicle_seller) {
            $notification = trans('translate.Access denied');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('user.select-car-purpose')->with($notification);
        }

        $setting = Setting::first();
        $feeFreeModeEnabled = $setting && $setting->fee_free_mode == 'enable';

        if($user->is_dealer){
            if ($feeFreeModeEnabled) {
                // allow dealer posting without package during launch free period
            } else {
            $active_plan = SubscriptionHistory::where('user_id', $user->id)->latest()->first();

            if(!$active_plan){
                $notification=  trans('translate.Please enroll first');
                $notification=array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->route('pricing-plan')->with($notification);
            }

            $expiration_date = $active_plan->expiration_date;

            if($expiration_date != 'lifetime'){
                if(date('Y-m-d') > $expiration_date){
                    $notification = trans('translate.Your plan is expired, please renew or re-order');
                    $notification = array('messege'=>$notification,'alert-type'=>'error');
                    return redirect()->route('pricing-plan')->with($notification);
                }
            }

            $max_car = $active_plan->max_car;

            $total_car = Car::where('agent_id', $user->id)->count();

            if($total_car >= $max_car){
                $notification = trans('translate.Your car limitation has exceeded');
                $notification = array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->route('user.select-car-purpose')->with($notification);
            }
            }
        }else{
            $pendingIndividualPaymentQuery = IndividualAdPayment::where('user_id', $user->id)
                ->where('status', 'success')
                ->whereNull('consumed_at');

            if(!$feeFreeModeEnabled){
                $pendingIndividualPaymentQuery->where('payment_method', '!=', 'Free');
            }

            $hasUnusedPayment = $pendingIndividualPaymentQuery->exists();

            if(!$hasUnusedPayment){
                if($feeFreeModeEnabled){
                    IndividualAdPayment::create([
                        'user_id' => $user->id,
                        'car_id' => null,
                        'amount' => 0,
                        'currency' => 'EUR',
                        'payment_method' => 'Free',
                        'status' => 'success',
                        'transaction_id' => 'free_period',
                        'consumed_at' => null,
                    ]);
                }else{
                    $notification = trans('translate.Please complete payment to post your ad');
                    $notification = array('messege'=>$notification,'alert-type'=>'error');
                    return redirect()->route('user.select-car-purpose')->with($notification);
                }
            }
        }

        $normalizeYoutubeId = function ($value) {
            $value = trim((string) $value);
            if ($value === '') {
                return null;
            }

            if (preg_match('~^[a-zA-Z0-9_-]{11}$~', $value)) {
                return $value;
            }

            $url = $value;
            if (!preg_match('~^https?://~i', $url)) {
                $url = 'https://' . ltrim($url, '/');
            }

            $parts = @parse_url($url);
            if (!is_array($parts)) {
                return $value;
            }

            $host = strtolower((string) ($parts['host'] ?? ''));
            $path = (string) ($parts['path'] ?? '');
            $query = (string) ($parts['query'] ?? '');

            if (str_contains($host, 'youtu.be')) {
                $id = trim($path, '/');
                $id = explode('/', $id)[0] ?? '';
                return $id !== '' ? $id : $value;
            }

            if (str_contains($host, 'youtube.com') || str_contains($host, 'youtube-nocookie.com')) {
                parse_str($query, $qs);
                if (!empty($qs['v'])) {
                    return (string) $qs['v'];
                }

                if (preg_match('~/(shorts|embed)/([^/?#]+)~i', $path, $m)) {
                    return (string) ($m[2] ?? $value);
                }
            }

            return $value;
        };

        if($request->purpose == 'Sale'){
            $brands = Brand::with('translate')
                ->where('status', 'enable')
                ->get()
                ->unique(function ($brand) {
                    $name = trim((string) optional($brand->translate)->name);
                    if ($name === '') {
                        $name = trim((string) ($brand->name ?? $brand->slug ?? $brand->id));
                    }
                    return Str::slug($name);
                })
                ->values();
            $cities = City::with(['front_translate', 'translate'])
                ->when($ireland?->id, function ($q) use ($ireland) {
                    $q->where('country_id', $ireland->id);
                })
                ->orderBy('id', 'asc')
                ->get();
            $features = Feature::with('translate')->get();
            $dealers = User::all();

            return view('car::frontend.create_sale_car', compact('brands', 'cities', 'features', 'dealers', 'countries', 'ireland', 'setting', 'feeFreeModeEnabled'));
        }else{
            abort(404);
        };

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CarRequest $request)
    {
        $authUser = Auth::guard('web')->user();

        if ($authUser?->is_dealer && !(bool) $authUser?->is_vehicle_seller) {
            $notification = trans('translate.Access denied');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('user.select-car-purpose')->with($notification);
        }

        $setting = Setting::first();
        $feeFreeModeEnabled = $setting && $setting->fee_free_mode == 'enable';

        if ($authUser && $authUser->is_dealer) {
            if (!$feeFreeModeEnabled) {
                $today = date('Y-m-d');
                $activePlan = SubscriptionHistory::where('user_id', $authUser->id)
                    ->where('status', 'active')
                    ->latest()
                    ->first();

                if (!$activePlan) {
                    $notification = trans('translate.Please enroll first');
                    $notification = array('messege' => $notification, 'alert-type' => 'error');
                    return redirect()->route('pricing-plan')->with($notification);
                }

                if ($activePlan->expiration_date !== 'lifetime' && $today > $activePlan->expiration_date) {
                    $notification = trans('translate.Your plan is expired, please renew or re-order');
                    $notification = array('messege' => $notification, 'alert-type' => 'error');
                    return redirect()->route('pricing-plan')->with($notification);
                }

                $maxCar = (int) ($activePlan->max_car ?? 0);
                if ($maxCar > 0) {
                    $currentActiveCount = Car::where('agent_id', $authUser->id)
                        ->where('approved_by_admin', 'approved')
                        ->where('status', 'enable')
                        ->where(function ($q) use ($today) {
                            $q->whereNull('expired_date')->orWhere('expired_date', '>=', $today);
                        })
                        ->count();

                    if ($currentActiveCount >= $maxCar) {
                        $notification = trans('translate.Your car limitation has exceeded');
                        $notification = array('messege' => $notification, 'alert-type' => 'error');
                        return redirect()->route('user.select-car-purpose')->with($notification);
                    }
                }
            }
        }

        $pendingIndividualPayment = null;
        if($authUser && !$authUser->is_dealer){
            $setting = Setting::first();
            $feeFreeModeEnabled = $setting && $setting->fee_free_mode == 'enable';
            $pendingIndividualPaymentQuery = IndividualAdPayment::where('user_id', $authUser->id)
                ->where('status', 'success')
                ->whereNull('consumed_at');

            if(!$feeFreeModeEnabled){
                $pendingIndividualPaymentQuery->where('payment_method', '!=', 'Free');
            }

            $pendingIndividualPayment = $pendingIndividualPaymentQuery
                ->orderBy('id', 'asc')
                ->first();

            if(!$pendingIndividualPayment){
                if($feeFreeModeEnabled){
                    $pendingIndividualPayment = IndividualAdPayment::create([
                        'user_id' => $authUser->id,
                        'car_id' => null,
                        'amount' => 0,
                        'currency' => 'EUR',
                        'payment_method' => 'Free',
                        'status' => 'success',
                        'transaction_id' => 'free_period',
                        'consumed_at' => null,
                    ]);
                }else{
                    $notification = trans('translate.Please complete payment to post your ad');
                    $notification = array('messege'=>$notification,'alert-type'=>'error');
                    return redirect()->back()->with($notification);
                }
            }
        }

        $car = new Car();

        $galleryFiles = $request->file('gallery_images') ?? [];
        if (count($galleryFiles) > 8) {
            return redirect()->back()->withInput()->withErrors([
                'gallery_images' => __('You can upload maximum 8 images only.'),
            ]);
        }
        $primaryImage = $galleryFiles[0] ?? null;

        if($primaryImage) {
            $image_name = 'car'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name = 'uploads/custom-images/'.$image_name;
            $manager = new ImageManager(['driver' => 'gd']);
            $image = $manager->make($primaryImage);
            fixImageOrientation($image, $primaryImage->getRealPath());
            $image->resize(1905, 1080, function($constraint){
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $watermarkUser = $authUser ? User::findOrFail($authUser->id) : User::findOrFail($request->agent_id);
            $author_name = '©'. $watermarkUser->name;
            $author_name = explode(' ', trim($author_name))[0];

            $image->text($author_name, $image->width() / 2, $image->height() - 50, function($font) {
                $font->file(public_path('fonts/static/Quicksand-Bold.ttf'));
                $font->size(40);
                $font->color([255, 255, 255, 0.5]);
                $font->align('center');
                $font->valign('bottom');
            });

            $image->encode('webp', 75)->save(public_path().'/'.$image_name);
            $car->thumb_image = $image_name;
        }

        if($request->video_image){
            $image_name = 'car-video-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/custom-images/'.$image_name;
            $videoImg = Image::make($request->video_image);
            fixImageOrientation($videoImg, $request->video_image->getRealPath());
            $videoImg->resize(1905, 1080, function($constraint){
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $videoImg->encode('webp', 75)->save(public_path().'/'.$image_name);
            $car->video_image = $image_name;
        }

        $normalizeYoutubeId = function ($value) {
            $value = trim((string) $value);
            if ($value === '') {
                return null;
            }

            if (preg_match('~^[a-zA-Z0-9_-]{11}$~', $value)) {
                return $value;
            }

            $url = $value;
            if (!preg_match('~^https?://~i', $url)) {
                $url = 'https://' . ltrim($url, '/');
            }

            $parts = @parse_url($url);
            if (!is_array($parts)) {
                return $value;
            }

            $host = strtolower((string) ($parts['host'] ?? ''));
            $path = (string) ($parts['path'] ?? '');
            $query = (string) ($parts['query'] ?? '');

            if (str_contains($host, 'youtu.be')) {
                $id = trim($path, '/');
                $id = explode('/', $id)[0] ?? '';
                return $id !== '' ? $id : $value;
            }

            if (str_contains($host, 'youtube.com') || str_contains($host, 'youtube-nocookie.com')) {
                parse_str($query, $qs);
                if (!empty($qs['v'])) {
                    return (string) $qs['v'];
                }

                if (preg_match('~/(shorts|embed)/([^/?#]+)~i', $path, $m)) {
                    return (string) ($m[2] ?? $value);
                }
            }

            return $value;
        };

        $car->agent_id = $authUser ? $authUser->id : $request->agent_id;
        $isRegisteredVehicle = $request->input('vehicle_source', 'registered') === 'registered';
        $car->brand_id = $isRegisteredVehicle ? null : $request->brand_id;
        $ireland = Country::whereRaw('LOWER(name) = ?', ['ireland'])->first();
        $car->country_id = $request->country_id ?: ($ireland?->id ?? null);
        $car->city_id = $request->city_id ?: ($authUser?->city_id ?? null);
        $car->slug = $request->slug;
        $car->features = json_encode($request->features);
        $car->purpose = $request->purpose;
        $car->rent_period = null;
        $car->condition = $request->condition;
        $car->regular_price = $request->price;
        $car->offer_price = null;
        $car->video_id = $normalizeYoutubeId($request->video_id);
        $car->google_map = null;
        $car->body_type = $request->body_type;
        $car->engine_size = $request->engine_size;
        $car->drive = $request->drive;
        $car->interior_color = $request->interior_color;
        $car->exterior_color = $request->exterior_color;
        $car->year = $request->year;
        $mileageVal = trim((string) $request->mileage);
        if ($mileageVal === '') {
            $car->mileage = null;
            $car->mileage_unit = null;
        } else {
            $car->mileage = $request->mileage;
            $car->mileage_unit = $request->mileage_unit;
        }
        $car->number_of_owner = $request->number_of_owner;
        $car->fuel_type = $request->fuel_type;
        $car->transmission = $request->transmission;
        $car->car_model = $request->car_model;
        $car->seller_type = ($authUser && $authUser->is_dealer) ? 'Dealer' : 'Personal';

        if (Schema::hasColumn('cars', 'warranty_months')) {
            $warrantyMonths = $request->input('warranty_months');
            if ($authUser && $authUser->is_dealer) {
                $car->warranty_months = is_numeric($warrantyMonths) ? (int) $warrantyMonths : null;
            } else {
                $car->warranty_months = null;
            }
        }

        $car->status = 'enable';
        $car->approved_by_admin = 'approved';

        $car->motorcheck_reg = $isRegisteredVehicle ? $request->motorcheck_reg : null;
        $car->motorcheck_make = $isRegisteredVehicle ? $request->motorcheck_make : null;
        $car->motorcheck_model = $isRegisteredVehicle ? $request->motorcheck_model : null;
        $car->motorcheck_version = $isRegisteredVehicle ? $request->motorcheck_version : null;
        $car->motorcheck_body = $isRegisteredVehicle ? $request->motorcheck_body : null;
        $car->motorcheck_doors = $isRegisteredVehicle ? $request->motorcheck_doors : null;
        $car->motorcheck_reg_date = $isRegisteredVehicle ? $request->motorcheck_reg_date : null;
        $car->motorcheck_engine_cc = $isRegisteredVehicle ? $request->motorcheck_engine_cc : null;
        $car->motorcheck_colour = $isRegisteredVehicle ? $request->motorcheck_colour : null;
        $car->motorcheck_fuel = $isRegisteredVehicle ? $request->motorcheck_fuel : null;
        $car->motorcheck_transmission = $isRegisteredVehicle ? $request->motorcheck_transmission : null;
        $car->motorcheck_no_of_owners = $isRegisteredVehicle ? $request->motorcheck_no_of_owners : null;
        $car->motorcheck_tax_class = $isRegisteredVehicle ? $request->motorcheck_tax_class : null;
        $car->motorcheck_tax_expiry_date = $isRegisteredVehicle ? $request->motorcheck_tax_expiry_date : null;
        $car->motorcheck_nct_expiry_date = $isRegisteredVehicle ? $request->motorcheck_nct_expiry_date : null;
        $car->motorcheck_co2_emissions = $isRegisteredVehicle ? $request->motorcheck_co2_emissions : null;
        $car->motorcheck_last_date_of_sale = $isRegisteredVehicle ? $request->motorcheck_last_date_of_sale : null;
        $car->motorcheck_raw = $isRegisteredVehicle ? $request->motorcheck_raw : null;
        $car->save();

        if($pendingIndividualPayment){
            $car->is_paid = 1;
            $car->save();

            $pendingIndividualPayment->car_id = $car->id;
            $pendingIndividualPayment->consumed_at = now();
            $pendingIndividualPayment->save();
        }

        foreach ($galleryFiles as $index => $image) {
            if (!$image) {
                continue;
            }

            $gallery_image = new CarGallery();

            if($image) {
                $image_name = 'car-gallery'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
                $image_name = 'uploads/custom-images/'.$image_name;
                $manager = new ImageManager(['driver' => 'gd']);
                $imgSource = $image;
                $image = $manager->make($image);
                fixImageOrientation($image, $imgSource->getRealPath());
                $image->resize(1905, 1080, function($constraint){
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $user = User::findOrFail($car->agent_id);

                $author_name = '©'. $user->name;
                $author_name = explode(' ', trim($author_name))[0];

                $image->text($author_name, $image->width() / 2, $image->height() - 50, function($font) {
                    $font->file(public_path('fonts/static/Quicksand-Bold.ttf'));
                    $font->size(40);
                    $font->color([255, 255, 255, 0.5]);
                    $font->align('center');
                    $font->valign('bottom');
                });

                $image->encode('webp', 75)->save(public_path().'/'.$image_name);

                $gallery_image->image = $image_name;
            }

            $gallery_image->car_id = $car->id;
            $gallery_image->save();
        }

        $user = Auth::guard('web')->user();

        $user = Auth::guard('web')->user();
        $setting = Setting::first();
        $feeFreeModeEnabled = $setting && $setting->fee_free_mode == 'enable';

        if($user && $user->is_dealer){
            if ($feeFreeModeEnabled) {
                $car->expired_date = date('Y-m-d', strtotime('+30 days'));
                $car->save();
            } else {
                $active_plan = SubscriptionHistory::where('user_id', $user->id)
                    ->where('status', 'active')
                    ->latest()
                    ->first();

                if ($active_plan) {
                    if($active_plan->expiration_date == 'lifetime'){
                        $car->expired_date = date('Y-m-d', strtotime('+30 days'));
                        $car->save();
                    }else{
                        $perAdExpiry = date('Y-m-d', strtotime('+30 days'));
                        $car->expired_date = $active_plan->expiration_date < $perAdExpiry ? $active_plan->expiration_date : $perAdExpiry;
                        $car->save();
                    }
                }
            }
        }else{
            $car->expired_date = date('Y-m-d', strtotime('+30 days'));
            $car->save();
        }

        $languages = Language::all();

        $addressText = trim((string) $request->input('address', ''));
        if ($addressText === '') {
            try {
                $cityId = $request->input('city_id');
                if ($cityId) {
                    $cityRow = \Modules\City\Entities\City::find($cityId);
                    if ($cityRow && !empty($cityRow->name)) {
                        $addressText = trim((string) $cityRow->name);
                    }
                }
            } catch (\Throwable $e) {
                $addressText = '';
            }
        }
        if ($addressText === '') {
            $addressText = '-';
        }

        foreach($languages as $language){
            $car_translate = new CarTranslation();
            $car_translate->lang_code = $language->lang_code;
            $car_translate->car_id = $car->id;
            $car_translate->title = $request->title;
            $car_translate->description = $request->description;
            $car_translate->video_description = $request->video_description;
            $car_translate->address = $addressText;
            $car_translate->seo_title = $request->seo_title ? $request->seo_title : $request->title;
            $car_translate->seo_description = $request->seo_description ? $request->seo_description : $request->title;
            $car_translate->save();
        }


        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('user.car.index')->with($notification);
    }

    /**
     * Column definition for the dealer bulk car upload CSV (no images).
     */
    private function bulkCarCsvColumns(): array
    {
        return [
            'title',
            'description',
            'brand',
            'car_model',
            'condition',
            'price',
            'year',
            'mileage',
            'mileage_unit',
            'fuel_type',
            'transmission',
            'body_type',
            'engine_size',
            'drive',
            'interior_color',
            'exterior_color',
            'number_of_owner',
            'warranty_months',
            'city',
        ];
    }

    private function generateUniqueCarSlug(string $title): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = 'car';
        }

        $slug = $base;
        $i = 1;
        while (Car::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    private function ensureDealerVehicleSeller()
    {
        $authUser = Auth::guard('web')->user();
        if (!$authUser || !$authUser->is_dealer || !(bool) $authUser->is_vehicle_seller) {
            return null;
        }
        return $authUser;
    }

    /**
     * Show the bulk car upload page (dealers only).
     */
    public function bulk_upload_form()
    {
        $authUser = $this->ensureDealerVehicleSeller();
        if (!$authUser) {
            $notification = ['messege' => trans('translate.Access denied'), 'alert-type' => 'error'];
            return redirect()->route('user.select-car-purpose')->with($notification);
        }

        return view('car::frontend.bulk_upload_car', [
            'columns' => $this->bulkCarCsvColumns(),
        ]);
    }

    /**
     * Download a sample CSV with all the supported car fields (no images).
     */
    public function bulk_upload_sample()
    {
        $authUser = $this->ensureDealerVehicleSeller();
        if (!$authUser) {
            $notification = ['messege' => trans('translate.Access denied'), 'alert-type' => 'error'];
            return redirect()->route('user.select-car-purpose')->with($notification);
        }

        $columns = $this->bulkCarCsvColumns();

        $example = [
            'title' => '2018 Toyota Corolla 1.4 Diesel',
            'description' => 'Full service history, one owner, excellent condition.',
            'brand' => 'Toyota',
            'car_model' => 'Corolla',
            'condition' => 'Used',
            'price' => '15500',
            'year' => '2018',
            'mileage' => '85000',
            'mileage_unit' => 'km',
            'fuel_type' => 'Diesel',
            'transmission' => 'Manual',
            'body_type' => 'Saloon',
            'engine_size' => '1400',
            'drive' => 'Front Wheel Drive',
            'interior_color' => 'Black',
            'exterior_color' => 'White',
            'number_of_owner' => '1',
            'warranty_months' => '12',
            'city' => 'Dublin',
        ];

        $filename = 'bulk-car-upload-sample.csv';

        $callback = function () use ($columns, $example) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);
            fputcsv($handle, array_map(fn ($c) => $example[$c] ?? '', $columns));
            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Process the uploaded CSV and create draft (disabled) car ads.
     */
    public function bulk_upload_store(Request $request)
    {
        $authUser = $this->ensureDealerVehicleSeller();
        if (!$authUser) {
            $notification = ['messege' => trans('translate.Access denied'), 'alert-type' => 'error'];
            return redirect()->route('user.select-car-purpose')->with($notification);
        }

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return redirect()->back()->withErrors(['csv_file' => __('Unable to read the uploaded file.')]);
        }

        $expectedColumns = $this->bulkCarCsvColumns();

        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            return redirect()->back()->withErrors(['csv_file' => __('The CSV file is empty.')]);
        }
        $header = array_map(fn ($h) => strtolower(trim((string) $h)), $header);

        // Build name -> id lookups (case-insensitive) for brand and city.
        $brandMap = [];
        foreach (\Modules\Brand\Entities\BrandTranslation::all() as $bt) {
            $key = strtolower(trim((string) $bt->name));
            if ($key !== '' && !isset($brandMap[$key])) {
                $brandMap[$key] = $bt->brand_id;
            }
        }
        $cityMap = [];
        foreach (\Modules\City\Entities\CityTranslation::all() as $ct) {
            $key = strtolower(trim((string) $ct->name));
            if ($key !== '' && !isset($cityMap[$key])) {
                $cityMap[$key] = $ct->city_id;
            }
        }

        $ireland = Country::whereRaw('LOWER(name) = ?', ['ireland'])->first();
        $languages = Language::all();

        // Determine draft expiry based on plan / fee-free mode.
        $setting = Setting::first();
        $feeFreeModeEnabled = $setting && $setting->fee_free_mode == 'enable';
        $expiredDate = date('Y-m-d', strtotime('+30 days'));
        if (!$feeFreeModeEnabled) {
            $activePlan = SubscriptionHistory::where('user_id', $authUser->id)
                ->where('status', 'active')
                ->latest()
                ->first();
            if ($activePlan && $activePlan->expiration_date !== 'lifetime') {
                $expiredDate = $activePlan->expiration_date < $expiredDate ? $activePlan->expiration_date : $expiredDate;
            }
        }

        $created = 0;
        $errors = [];
        $rowNum = 1; // header is row 1

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;

            // Skip fully empty rows.
            if (count(array_filter($row, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }

            // Map row by header where possible, else by expected column order.
            $data = [];
            foreach ($expectedColumns as $idx => $col) {
                $value = '';
                $headerIdx = array_search($col, $header, true);
                if ($headerIdx !== false && isset($row[$headerIdx])) {
                    $value = $row[$headerIdx];
                } elseif (isset($row[$idx])) {
                    $value = $row[$idx];
                }
                $data[$col] = trim((string) $value);
            }

            // Required validations.
            if ($data['title'] === '') {
                $errors[] = __('Row :n: Title is required.', ['n' => $rowNum]);
                continue;
            }
            if ($data['description'] === '') {
                $errors[] = __('Row :n: Description is required.', ['n' => $rowNum]);
                continue;
            }
            if ($data['condition'] === '' || !in_array(strtolower($data['condition']), ['new', 'used'], true)) {
                $errors[] = __('Row :n: Condition must be New or Used.', ['n' => $rowNum]);
                continue;
            }
            if ($data['price'] === '' || !is_numeric($data['price'])) {
                $errors[] = __('Row :n: Price must be a number.', ['n' => $rowNum]);
                continue;
            }
            $cityKey = strtolower($data['city']);
            if ($cityKey === '' || !isset($cityMap[$cityKey])) {
                $errors[] = __('Row :n: City ":c" was not found.', ['n' => $rowNum, 'c' => $data['city']]);
                continue;
            }

            $brandId = null;
            if ($data['brand'] !== '') {
                $brandKey = strtolower($data['brand']);
                if (isset($brandMap[$brandKey])) {
                    $brandId = $brandMap[$brandKey];
                } else {
                    $errors[] = __('Row :n: Brand ":b" was not found.', ['n' => $rowNum, 'b' => $data['brand']]);
                    continue;
                }
            }

            $mileageUnit = strtolower($data['mileage_unit']);
            if ($data['mileage'] !== '' && !in_array($mileageUnit, ['km', 'miles'], true)) {
                $mileageUnit = 'km';
            }

            $car = new Car();
            $car->agent_id = $authUser->id;
            $car->brand_id = $brandId;
            $car->country_id = $ireland?->id;
            $car->city_id = $cityMap[$cityKey];
            $car->slug = $this->generateUniqueCarSlug($data['title']);
            $car->features = json_encode([]);
            $car->purpose = 'Sale';
            $car->rent_period = null;
            $car->condition = ucfirst(strtolower($data['condition']));
            $car->regular_price = $data['price'];
            $car->offer_price = null;
            $car->body_type = $data['body_type'] ?: null;
            $car->engine_size = $data['engine_size'] ?: null;
            $car->drive = $data['drive'] ?: null;
            $car->interior_color = $data['interior_color'] ?: null;
            $car->exterior_color = $data['exterior_color'] ?: null;
            $car->year = $data['year'] ?: null;
            if ($data['mileage'] === '') {
                $car->mileage = null;
                $car->mileage_unit = null;
            } else {
                $car->mileage = $data['mileage'];
                $car->mileage_unit = $mileageUnit;
            }
            $car->number_of_owner = $data['number_of_owner'] ?: null;
            $car->fuel_type = $data['fuel_type'] ?: null;
            $car->transmission = $data['transmission'] ?: null;
            $car->car_model = $data['car_model'] ?: null;
            $car->seller_type = 'Dealer';

            if (Schema::hasColumn('cars', 'warranty_months')) {
                $car->warranty_months = is_numeric($data['warranty_months']) ? (int) $data['warranty_months'] : null;
            }

            // Draft: pending review by dealer (disabled until images added & enabled).
            $car->status = 'disable';
            $car->approved_by_admin = 'approved';
            $car->expired_date = $expiredDate;
            $car->save();

            $addressText = $data['city'] !== '' ? $data['city'] : '-';
            foreach ($languages as $language) {
                $t = new CarTranslation();
                $t->lang_code = $language->lang_code;
                $t->car_id = $car->id;
                $t->title = $data['title'];
                $t->description = $data['description'];
                $t->video_description = null;
                $t->address = $addressText;
                $t->seo_title = $data['title'];
                $t->seo_description = $data['title'];
                $t->save();
            }

            $created++;
        }

        fclose($handle);

        if ($created > 0) {
            $msg = __(':n car ad(s) imported as drafts. Add images and enable each ad to publish.', ['n' => $created]);
            if (count($errors) > 0) {
                $msg .= ' ' . __(':e row(s) were skipped.', ['e' => count($errors)]);
            }
            $notification = ['messege' => $msg, 'alert-type' => 'success'];
            return redirect()->route('user.car.index')->with($notification)->with('bulk_errors', $errors);
        }

        $notification = ['messege' => __('No car ads were imported. Please check the file and try again.'), 'alert-type' => 'error'];
        return redirect()->back()->with($notification)->with('bulk_errors', $errors);
    }

    public function motorcheck_lookup(Request $request)
    {
        $request->validate([
            'registration_number' => ['required', 'string', 'max:50'],
        ]);

        $baseUrl = config('services.motorcheck.base_url');
        $endpoint = config('services.motorcheck.endpoint');
        $apiKey = config('services.motorcheck.api_key');
        $registrationParam = config('services.motorcheck.registration_param', 'registration');
        $authType = config('services.motorcheck.auth_type', 'basic');
        $basicUsername = config('services.motorcheck.basic_username');
        $basicPassword = config('services.motorcheck.basic_password');
        $userHeader = config('services.motorcheck.user_header', 'x-username');
        $userValue = config('services.motorcheck.user_value');

        $missing = [];
        if (!$baseUrl) {
            $missing[] = 'MOTORCHECK_BASE_URL';
        }
        if ($authType === 'basic') {
            if (!$basicUsername) {
                $missing[] = 'MOTORCHECK_BASIC_USERNAME';
            }
            if (!$basicPassword) {
                $missing[] = 'MOTORCHECK_BASIC_PASSWORD';
            }
        } else {
            if (!$userValue) {
                $missing[] = 'MOTORCHECK_USER_VALUE';
            }
            if (!$apiKey) {
                $missing[] = 'MOTORCHECK_API_KEY';
            }
        }

        if (count($missing) > 0) {
            return response()->json([
                'message' => 'Vehicle lookup is not configured. Please contact support.',
                'missing' => $missing,
            ], 500);
        }

        Log::info('MotorCheck lookup config', [
            'base_url' => $baseUrl,
            'endpoint_template' => $endpoint,
            'auth_type' => $authType,
            'user_header' => $userHeader,
            'user_value' => $userValue,
        ]);

        $registration = trim($request->input('registration_number'));

        if (!$endpoint) {
            $endpoint = '/vehicle/reg/:reg/lookup';
        }

        $endpoint = str_replace([':reg', '{reg}'], urlencode($registration), $endpoint);

        try {
            $http = Http::baseUrl($baseUrl)
                ->acceptJson()
                ->connectTimeout(5)
                ->timeout(15)
                ->retry(2, 250, function ($exception) {
                    return $exception instanceof ConnectionException;
                });

            if ($authType === 'basic') {
                $http = $http->withBasicAuth($basicUsername, $basicPassword);
            } else {
                $http = $http->withHeaders([
                    'x-api-key' => $apiKey,
                    $userHeader => $userValue,
                ]);
            }

            $response = $http->get($endpoint, array_merge(
                ['format' => 'json'],
                $registrationParam ? [$registrationParam => $registration] : []
            ));

            if (!$response->successful()) {
                Log::warning('MotorCheck lookup failed', [
                    'status' => $response->status(),
                    'registration' => $registration,
                    'base_url' => $baseUrl,
                    'endpoint' => $endpoint,
                    'user_header' => $userHeader,
                    'user_value' => $userValue,
                    'response' => $response->json(),
                ]);

                return response()->json([
                    'message' => 'Registration number not found. Please check the number and try again.',
                    'status' => $response->status(),
                    'error' => $response->json(),
                    'config' => config('app.debug') ? [
                        'base_url' => $baseUrl,
                        'endpoint' => $endpoint,
                        'user_header' => $userHeader,
                        'user_value' => $userValue,
                    ] : null,
                ], 422);
            }

            $data = $response->json();

            $vehicle = $data['vehicle'] ?? [];

            $regDate = $vehicle['reg_date'] ?? null;
            $year = null;
            if (is_string($regDate) && strlen($regDate) >= 4) {
                $year = substr($regDate, 0, 4);
            }

            $mapped = [
                'registration_number' => $vehicle['reg'] ?? $registration,
                'make' => $vehicle['make'] ?? null,
                'model' => $vehicle['model'] ?? null,
                'body_type' => $vehicle['body'] ?? null,
                'year' => $year,
                'engine_size' => isset($vehicle['engine_cc']) ? (string) $vehicle['engine_cc'] : null,
                'fuel_type' => $vehicle['fuel'] ?? null,
                'transmission' => $vehicle['transmission'] ?? null,
                'exterior_color' => $vehicle['colour'] ?? null,
                'number_of_owner' => $vehicle['no_of_owners'] ?? null,

                'motorcheck_reg' => $vehicle['reg'] ?? null,
                'motorcheck_make' => $vehicle['make'] ?? null,
                'motorcheck_model' => $vehicle['model'] ?? null,
                'motorcheck_version' => $vehicle['version'] ?? null,
                'motorcheck_body' => $vehicle['body'] ?? null,
                'motorcheck_doors' => $vehicle['doors'] ?? null,
                'motorcheck_reg_date' => $vehicle['reg_date'] ?? null,
                'motorcheck_engine_cc' => $vehicle['engine_cc'] ?? null,
                'motorcheck_colour' => $vehicle['colour'] ?? null,
                'motorcheck_fuel' => $vehicle['fuel'] ?? null,
                'motorcheck_transmission' => $vehicle['transmission'] ?? null,
                'motorcheck_no_of_owners' => $vehicle['no_of_owners'] ?? null,
                'motorcheck_tax_class' => $vehicle['tax_class'] ?? null,
                'motorcheck_tax_expiry_date' => $vehicle['tax_expiry_date'] ?? null,
                'motorcheck_nct_expiry_date' => $vehicle['NCT_expiry_date'] ?? null,
                'motorcheck_co2_emissions' => $vehicle['co2_emissions'] ?? null,
                'motorcheck_last_date_of_sale' => $vehicle['last_date_of_sale'] ?? null,
            ];

            return response()->json([
                'message' => 'ok',
                'raw' => $data,
                'vehicle' => $vehicle,
                'mapped' => $mapped,
            ]);
        } catch (ConnectionException $e) {
            Log::error('MotorCheck lookup connection error', [
                'registration' => $registration,
                'endpoint' => $endpoint,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Vehicle lookup service is currently unavailable. Please try again later.',
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 504);
        } catch (\Throwable $e) {
            Log::error('MotorCheck lookup error', [
                'registration' => $registration,
                'endpoint' => $endpoint,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Unable to retrieve vehicle details. Please check the registration number and try again.',
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request, $id)
    {

        $ireland = Country::where('code', 'IE')->orWhere('name', 'Ireland')->first();

        $countries = $ireland ? collect([$ireland]) : collect();

        $user = Auth::guard('web')->user();

        $car = Car::find($id);
        if (!$car) {
            Log::warning('car.edit.not_found', [
                'car_id' => $id,
                'auth_user_id' => $user?->id,
                'db_connection' => config('database.default'),
                'db_database' => \DB::connection()->getDatabaseName(),
            ]);
            abort(404);
        }

        if (!$user || (int) $car->agent_id !== (int) $user->id) {
            Log::warning('car.edit.unauthorized', [
                'car_id' => $car->id,
                'auth_user_id' => $user?->id,
                'car_agent_id' => $car->agent_id,
                'db_connection' => config('database.default'),
                'db_database' => \DB::connection()->getDatabaseName(),
            ]);
            abort(403);
        }

        $langCode = (string) $request->get('lang_code', admin_lang());
        $car_translate = CarTranslation::where(['car_id' => $id, 'lang_code' => $langCode])->first();
        if (!$car_translate && $langCode !== admin_lang()) {
            $car_translate = CarTranslation::where(['car_id' => $id, 'lang_code' => admin_lang()])->first();
        }

        if($car->purpose == 'Rent'){

            abort(404);

        }else{

            $brands = Brand::with('translate')->where('status', 'enable')->get();
            $cities = City::with(['front_translate', 'translate'])
                ->where('country_id', $ireland?->id ?? 0)
                ->orderBy('id', 'asc')
                ->get();
            $features = Feature::with('translate')->get();
            $dealers = User::all();

            $existing_features = array();
            if($car->features != 'null'){
                if(is_array(json_decode($car->features))){
                    $existing_features = json_decode($car->features);
                }
            }

            return view('car::frontend.edit_sale_car', compact('brands', 'cities', 'features', 'dealers', 'car', 'car_translate', 'existing_features', 'countries', 'ireland'));

        }

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CarRequest $request, $id)
    {
        $authUser = Auth::guard('web')->user();
        $car = Car::find($id);

        if (!$car) {
            abort(404);
        }

        $normalizeYoutubeId = function ($value) {
            $value = trim((string) $value);
            if ($value === '') {
                return null;
            }

            if (preg_match('~^[a-zA-Z0-9_-]{11}$~', $value)) {
                return $value;
            }

            $url = $value;
            if (!preg_match('~^https?://~i', $url)) {
                $url = 'https://' . ltrim($url, '/');
            }

            $parts = @parse_url($url);
            if (!is_array($parts)) {
                return $value;
            }

            $host = strtolower((string) ($parts['host'] ?? ''));
            $path = (string) ($parts['path'] ?? '');
            $query = (string) ($parts['query'] ?? '');

            if (str_contains($host, 'youtu.be')) {
                $id = trim($path, '/');
                $id = explode('/', $id)[0] ?? '';
                return $id !== '' ? $id : $value;
            }

            if (str_contains($host, 'youtube.com') || str_contains($host, 'youtube-nocookie.com')) {
                parse_str($query, $qs);
                if (!empty($qs['v'])) {
                    return (string) $qs['v'];
                }

                if (preg_match('~/(shorts|embed)/([^/?#]+)~i', $path, $m)) {
                    return (string) ($m[2] ?? $value);
                }
            }

            return $value;
        };

        if (!$authUser || (int) $car->agent_id !== (int) $authUser->id) {
            Log::warning('car.update.unauthorized', [
                'car_id' => $car->id,
                'auth_user_id' => $authUser?->id,
                'car_agent_id' => $car->agent_id,
                'request_agent_id' => $request->input('agent_id'),
                'db_connection' => config('database.default'),
                'db_database' => \DB::connection()->getDatabaseName(),
            ]);
            abort(403);
        }

        Log::info('car.update.start', [
            'car_id' => $car->id,
            'auth_user_id' => $authUser?->id,
            'car_agent_id_before' => $car->agent_id,
            'request_agent_id' => $request->input('agent_id'),
            'request_warranty_months' => $request->input('warranty_months'),
            'request_method' => $request->method(),
            'request_url' => $request->fullUrl(),
            'db_connection' => config('database.default'),
            'db_database' => \DB::connection()->getDatabaseName(),
        ]);

        $galleryFiles = $request->file('gallery_images') ?? [];
        if (($car->galleries()->count() + count($galleryFiles)) > 8) {
            return redirect()->back()->withInput()->withErrors([
                'gallery_images' => __('You can upload maximum 8 images only.'),
            ]);
        }
        $setThumbFromFirstNewGallery = count($galleryFiles) > 0;
        $oldThumbImage = $setThumbFromFirstNewGallery ? $car->thumb_image : null;

        foreach ($galleryFiles as $index => $image) {
            $gallery_image = new CarGallery();

            if($image) {
                $image_name = 'car-gallery'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
                $image_name = 'uploads/custom-images/'.$image_name;
                $manager = new ImageManager(['driver' => 'gd']);
                $img = $manager->make($image);
                fixImageOrientation($img, $image->getRealPath());

                $user = User::findOrFail($car->agent_id);
                $author_name = '©'. $user->name;
                $author_name = explode(' ', trim($author_name))[0];

                $img->text($author_name, $img->width() / 2, $img->height() - 50, function($font) {
                    $font->file(public_path('fonts/static/Quicksand-Bold.ttf'));
                    $font->size(40);
                    $font->color([255, 255, 255, 0.5]);
                    $font->align('center');
                    $font->valign('bottom');
                });

                $img->encode('webp', 80)->save(public_path().'/'.$image_name);
                $gallery_image->image = $image_name;
            }

            $gallery_image->car_id = $car->id;
            $gallery_image->save();

            if ($setThumbFromFirstNewGallery && $index === 0 && !empty($gallery_image->image)) {
                $car->thumb_image = $gallery_image->image;
                $car->save();

                if (!empty($oldThumbImage) && $oldThumbImage !== $car->thumb_image) {
                    // Only delete the old thumb file if it is NOT referenced by any gallery image
                    $thumbUsedByGallery = CarGallery::where('car_id', $car->id)
                        ->where('image', $oldThumbImage)->exists();
                    if (!$thumbUsedByGallery && File::exists(public_path().'/'.$oldThumbImage)) {
                        @unlink(public_path().'/'.$oldThumbImage);
                    }
                }
            }
        }

        if($request->video_image){
            $old_image = $car->video_image;
            $image_name = 'car-video-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/custom-images/'.$image_name;
            $videoImg = Image::make($request->video_image);
            fixImageOrientation($videoImg, $request->video_image->getRealPath());
            $videoImg
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $car->video_image = $image_name;
            $car->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        $car->agent_id = $authUser?->id;
        $isRegisteredVehicle = $request->input('vehicle_source', 'registered') === 'registered';
        if ($isRegisteredVehicle) {
            $car->brand_id = null;
        } else {
            $car->brand_id = $request->brand_id;
        }
        $ireland = Country::whereRaw('LOWER(name) = ?', ['ireland'])->first();
        $car->country_id = $request->country_id ?: ($ireland?->id ?? null);
        $car->city_id = $request->city_id ?: ($authUser?->city_id ?? null);
        $car->slug = $request->slug;
        $car->features = json_encode($request->features);
        $car->purpose = $request->purpose;
        $car->rent_period = null;
        $car->condition = $request->condition;
        $car->regular_price = $request->price;
        $car->offer_price = null;
        $car->video_id = $normalizeYoutubeId($request->video_id);
        $car->google_map = null;
        $car->body_type = $request->body_type;
        $car->engine_size = $request->engine_size;
        $car->drive = $request->drive;
        $car->interior_color = $request->interior_color;
        $car->exterior_color = $request->exterior_color;
        $car->year = $request->year;
        $mileageVal = trim((string) $request->mileage);
        if ($mileageVal === '') {
            $car->mileage = null;
            $car->mileage_unit = null;
        } else {
            $car->mileage = $request->mileage;
            $car->mileage_unit = $request->mileage_unit;
        }
        $car->number_of_owner = $request->number_of_owner;
        $car->fuel_type = $request->fuel_type;
        $car->transmission = $request->transmission;
        $car->car_model = $request->car_model;
        $authUser = Auth::guard('web')->user();
        $car->seller_type = ($authUser && $authUser->is_dealer) ? 'Dealer' : 'Personal';

        if (Schema::hasColumn('cars', 'warranty_months')) {
            $warrantyMonths = $request->input('warranty_months');
            if ($authUser && $authUser->is_dealer) {
                $car->warranty_months = is_numeric($warrantyMonths) ? (int) $warrantyMonths : null;
            } else {
                $car->warranty_months = null;
            }
        }

        $car->motorcheck_reg = $request->motorcheck_reg;
        $car->motorcheck_make = $request->motorcheck_make;
        $car->motorcheck_model = $request->motorcheck_model;
        $car->motorcheck_version = $request->motorcheck_version;
        $car->motorcheck_body = $request->motorcheck_body;
        $car->motorcheck_doors = $request->motorcheck_doors;
        $car->motorcheck_reg_date = $request->motorcheck_reg_date;
        $car->motorcheck_engine_cc = $request->motorcheck_engine_cc;
        $car->motorcheck_colour = $request->motorcheck_colour;
        $car->motorcheck_fuel = $request->motorcheck_fuel;
        $car->motorcheck_transmission = $request->motorcheck_transmission;
        $car->motorcheck_no_of_owners = $request->motorcheck_no_of_owners;
        $car->motorcheck_tax_class = $request->motorcheck_tax_class;
        $car->motorcheck_tax_expiry_date = $request->motorcheck_tax_expiry_date;
        $car->motorcheck_nct_expiry_date = $request->motorcheck_nct_expiry_date;
        $car->motorcheck_co2_emissions = $request->motorcheck_co2_emissions;
        $car->motorcheck_last_date_of_sale = $request->motorcheck_last_date_of_sale;
        $car->motorcheck_raw = $request->motorcheck_raw;
        $car->save();

        $carStillExists = Car::where('id', $car->id)->exists();
        if (!$carStillExists) {
            Log::error('car.update.missing_after_save', [
                'car_id' => $car->id,
                'auth_user_id' => $authUser?->id,
                'db_connection' => config('database.default'),
                'db_database' => \DB::connection()->getDatabaseName(),
            ]);
        }

        Log::info('car.update.saved', [
            'car_id' => $car->id,
            'auth_user_id' => $authUser?->id,
            'car_agent_id_after' => $car->agent_id,
            'car_status' => $car->status,
            'car_approved_by_admin' => $car->approved_by_admin,
            'car_purpose' => $car->purpose,
            'car_warranty_months' => $car->warranty_months,
        ]);

        $translateId = $request->input('translate_id');
        $langCode = (string) $request->input('lang_code', admin_lang());

        $car_translate = null;
        if ($translateId) {
            $car_translate = CarTranslation::where('id', $translateId)
                ->where('car_id', $car->id)
                ->first();
        }

        if (!$car_translate) {
            $car_translate = CarTranslation::where(['car_id' => $car->id, 'lang_code' => $langCode])->first();
        }

        if (!$car_translate) {
            $car_translate = new CarTranslation();
            $car_translate->car_id = $car->id;
            $car_translate->lang_code = $langCode;
        }

        $addressText = trim((string) $request->input('address', ''));
        if ($addressText === '') {
            try {
                $cityId = $request->input('city_id');
                if ($cityId) {
                    $cityRow = \Modules\City\Entities\City::find($cityId);
                    if ($cityRow && !empty($cityRow->name)) {
                        $addressText = trim((string) $cityRow->name);
                    }
                }
            } catch (\Throwable $e) {
                $addressText = '';
            }
        }
        if ($addressText === '') {
            $addressText = '-';
        }

        $car_translate->title = $request->title;
        $car_translate->description = $request->description;
        $car_translate->video_description = $request->video_description;
        $car_translate->address = $addressText;
        $car_translate->seo_title = $request->seo_title ? $request->seo_title : $request->title;
        $car_translate->seo_description = $request->seo_description ? $request->seo_description : $request->title;
        $car_translate->save();

        $notification= trans('translate.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('user.car.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $authUser = Auth::guard('web')->user();
        $car = Car::query()
            ->where('id', $id)
            ->when($authUser, function ($q) use ($authUser) {
                $q->where('agent_id', $authUser->id);
            })
            ->firstOrFail();
        $old_image = $car->thumb_image;
        $old_video_image = $car->video_image;

        if($old_image){
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        if($old_video_image){
            if(File::exists(public_path().'/'.$old_video_image))unlink(public_path().'/'.$old_video_image);
        }

        CarTranslation::where('car_id',$id)->delete();
        Review::where('car_id',$id)->delete();
        Wishlist::where('car_id',$id)->delete();

        $galleries = CarGallery::where('car_id', $id)->get();
        foreach($galleries as $gallery){
            $old_image = $gallery->image;

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

            $gallery->delete();
        }

        $car->delete();

        $notification=  trans('translate.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function car_gallery($id){
        $car = Car::findOrFail($id);

        $galleries = CarGallery::where('car_id', $id)->get();

        return view('car::frontend.gallery', compact('car', 'galleries'));
    }

    public function upload_car_gallery(Request $request, $id){

        $car = Car::findOrFail($id);

        foreach ($request->file as $index => $image) {
            $gallery_image = new CarGallery();

            if($image) {

                $image_name = 'car-gallery'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
                $image_name = 'uploads/custom-images/'.$image_name;
                $manager = new ImageManager(['driver' => 'gd']);
                $imgSource = $image;
                $image = $manager->make($image);
                fixImageOrientation($image, $imgSource->getRealPath());

                $user = User::findOrFail($car->agent_id);

                $author_name = '©'. $user->name;

                $author_name = explode(' ', trim($author_name))[0];

                $image->text($author_name, $image->width() / 2, $image->height() - 50, function($font) {
                    $font->file(public_path('fonts/static/Quicksand-Bold.ttf'));
                    $font->size(40);
                    $font->color([255, 255, 255, 0.5]);
                    $font->align('center');
                    $font->valign('bottom');
                });

                $image->encode('webp', 80)->save(public_path().'/'.$image_name);

                $gallery_image->image = $image_name;

            }

            $gallery_image->car_id = $id;
            $gallery_image->save();
        }

        if ($gallery_image) {
            return response()->json([
                'message' => trans('translate.Images uploaded successfully'),
                'url' => route('user.car-gallery', $id),
            ]);
        } else {
             return response()->json([
                'message' => trans('translate.Images uploaded Failed'),
                'url' => route('user.car-gallery', $id),
            ]);
        }

    }

    public function delete_car_gallery($id){
        $gallery = CarGallery::findOrFail($id);
        $old_image = $gallery->image;
        $carId = $gallery->car_id;

        if($old_image){
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        $gallery->delete();

        if ($carId) {
            $car = Car::find($carId);
            if ($car) {
                $thumbExistsInGalleries = !empty($car->thumb_image)
                    && CarGallery::where('car_id', $carId)->where('image', $car->thumb_image)->exists();

                if ($car->thumb_image === $old_image || !$thumbExistsInGalleries) {
                    $nextThumb = CarGallery::where('car_id', $carId)->oldest('id')->value('image');
                    $car->thumb_image = $nextThumb ?: '';
                    $car->save();
                }
            }
        }

        $notification=  trans('translate.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }
}
