<?php

namespace Modules\Car\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Review;
use App\Models\Wishlist;
use Auth, Image, File, Str;
use Illuminate\Http\Request;
use Modules\Car\Entities\Car;

use Modules\City\Entities\City;
use Modules\Brand\Entities\Brand;
use Illuminate\Routing\Controller;
use Intervention\Image\ImageManager;
use Modules\Car\Entities\CarGallery;
use Modules\Country\Entities\Country;
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
use Illuminate\Support\Facades\Schema;
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

        $baseQuery = Car::where('agent_id', $user->id);

        $totalCount = (clone $baseQuery)->count();
        $activeCount = (clone $baseQuery)->where('approved_by_admin', 'approved')->count();
        $inactiveCount = max(0, $totalCount - $activeCount);

        $carsQuery = clone $baseQuery;
        if ($status === 'active') {
            $carsQuery->where('approved_by_admin', 'approved');
        } elseif ($status === 'inactive') {
            $carsQuery->where('approved_by_admin', '!=', 'approved');
        }

        $cars = $carsQuery->latest()->paginate(15)->appends($request->query());

        return view('car::frontend.index', [
            'cars' => $cars,
            'status' => $status,
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
        ]);
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
            $active_plan = SubscriptionHistory::where('user_id', $user->id)->latest()->first();

            if(!$active_plan){
                if($feeFreeModeEnabled){
                    $expiration_date = date('Y-m-d', strtotime('+90 days'));

                    $purchase = new SubscriptionHistory();
                    $purchase->order_id = substr(rand(0,time()),0,10);
                    $purchase->user_id = $user->id;
                    $purchase->subscription_plan_id = 0;
                    $purchase->max_car = (int) (env('DEALER_FREE_TRIAL_MAX_CAR') ?: 999999);
                    $purchase->featured_car = (int) (env('DEALER_FREE_TRIAL_FEATURED_CAR') ?: 0);
                    $purchase->plan_name = '3 Month Free Trial';
                    $purchase->plan_price = 0;
                    $purchase->expiration = 'trial';
                    $purchase->expiration_date = $expiration_date;
                    $purchase->status = 'active';
                    $purchase->payment_method = 'Free';
                    $purchase->payment_status = 'success';
                    $purchase->transaction = 'dealer_free_trial';
                    $purchase->save();

                    Car::where('agent_id', $user->id)->update(['expired_date' => $expiration_date]);
                    $active_plan = $purchase;
                }else{
                    $notification=  trans('translate.Please enroll first');
                    $notification=array('messege'=>$notification,'alert-type'=>'error');
                    return redirect()->route('pricing-plan')->with($notification);
                }
            }

            $expiration_date = $active_plan->expiration_date;

            if($expiration_date != 'lifetime'){
                if(date('Y-m-d') > $expiration_date){
                    $notification = trans('translate.Your plan is expired, please renew or re-order');
                    $notification = array('messege'=>$notification,'alert-type'=>'error');
                    return redirect()->route('user.pricing-plan')->with($notification);
                }
            }

            $max_car = $active_plan->max_car;

            $total_car = Car::where('agent_id', $user->id)->count();

            if($total_car >= $max_car){
                $notification = trans('translate.Your car limitation has exceeded');
                $notification = array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->route('user.select-car-purpose')->with($notification);
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

        if($request->purpose == 'Sale'){
            $brands = Brand::with('translate')->where('status', 'enable')->get();
            $cities = City::with(['front_translate', 'translate'])
                ->where('country_id', $ireland?->id ?? 0)
                ->orderBy('id', 'asc')
                ->get();
            $features = Feature::with('translate')->get();
            $dealers = User::all();

            return view('car::frontend.create_sale_car', compact('brands', 'cities', 'features', 'dealers', 'countries', 'ireland'));
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

            $user = User::findOrFail($request->agent_id);
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
            $car->thumb_image = $image_name;
        }

        if($request->video_image){
            $image_name = 'car-video-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/custom-images/'.$image_name;
            Image::make($request->video_image)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $car->video_image = $image_name;
        }

        $car->agent_id = $request->agent_id;
        $car->brand_id = $request->brand_id;
        $car->city_id = $request->city_id;
        $car->country_id = $request->country_id;
        $car->slug = $request->slug;
        $car->features = json_encode($request->features);
        $car->purpose = $request->purpose;
        $car->rent_period = null;
        $car->condition = $request->condition;
        $car->regular_price = $request->price;
        $car->offer_price = null;
        $car->video_id = $request->video_id;
        $car->google_map = null;
        $car->body_type = $request->body_type;
        $car->engine_size = $request->engine_size;
        $car->drive = $request->drive;
        $car->interior_color = $request->interior_color;
        $car->exterior_color = $request->exterior_color;
        $car->year = $request->year;
        $car->mileage = $request->mileage;
        $car->number_of_owner = $request->number_of_owner;
        $car->fuel_type = $request->fuel_type;
        $car->transmission = $request->transmission;
        $car->car_model = $request->car_model;
        $car->seller_type = ($authUser && $authUser->is_dealer) ? 'Dealer' : 'Personal';

        $car->status = 'enable';
        $car->approved_by_admin = 'approved';

        $isRegisteredVehicle = $request->input('vehicle_source', 'registered') === 'registered';

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
            $gallery_image = new CarGallery();

            if($image) {
                $image_name = 'car-gallery'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
                $image_name = 'uploads/custom-images/'.$image_name;
                $manager = new ImageManager(['driver' => 'gd']);
                $image = $manager->make($image);

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

            $gallery_image->car_id = $car->id;
            $gallery_image->save();
        }

        $user = Auth::guard('web')->user();

        if($user && $user->is_dealer){
            $active_plan = SubscriptionHistory::where('user_id', $user->id)->latest()->first();
            if($active_plan){
                if($active_plan->expiration_date == 'lifetime'){
                    $car->expired_date = null;
                    $car->save();
                }else{
                    $car->expired_date = $active_plan->expiration_date;
                    $car->save();
                }
            }
        }else{
            $car->expired_date = null;
            $car->save();
        }

        $languages = Language::all();
        foreach($languages as $language){
            $car_translate = new CarTranslation();
            $car_translate->lang_code = $language->lang_code;
            $car_translate->car_id = $car->id;
            $car_translate->title = $request->title;
            $car_translate->description = $request->description;
            $car_translate->video_description = $request->video_description;
            $car_translate->address = null;
            $car_translate->seo_title = $request->seo_title ? $request->seo_title : $request->title;
            $car_translate->seo_description = $request->seo_description ? $request->seo_description : $request->title;
            $car_translate->save();
        }


        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('user.car.index')->with($notification);
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
                'message' => 'MotorCheck configuration is missing',
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
                    'message' => 'MotorCheck lookup failed',
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
                'message' => 'MotorCheck service is unreachable (connection timeout). Please try again later.',
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 504);
        } catch (\Throwable $e) {
            Log::error('MotorCheck lookup error', [
                'registration' => $registration,
                'endpoint' => $endpoint,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'MotorCheck lookup error',
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

        $car = Car::where('agent_id', $user->id)->where('id', $id)->firstOrFail();

        $car_translate = CarTranslation::where(['car_id' => $id, 'lang_code' => admin_lang()])->first();

        if($car->purpose == 'Rent'){

            abort(404);

        }elseif($car->purpose == 'Sale'){

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

        }else{
            abort(404);
        }
;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CarRequest $request, $id)
    {
        $car = Car::findOrFail($id);

        $galleryFiles = $request->file('gallery_images') ?? [];
        if (($car->galleries()->count() + count($galleryFiles)) > 8) {
            return redirect()->back()->withInput()->withErrors([
                'gallery_images' => __('You can upload maximum 8 images only.'),
            ]);
        }
        $primaryImage = $galleryFiles[0] ?? null;

        if($primaryImage) {
            $old_image = $car->thumb_image;

            $image_name = 'car'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name = 'uploads/custom-images/'.$image_name;
            $manager = new ImageManager(['driver' => 'gd']);
            $image = $manager->make($primaryImage);

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

            $car->thumb_image = $image_name;
            $car->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        foreach ($galleryFiles as $index => $image) {
            $gallery_image = new CarGallery();

            if($image) {
                $image_name = 'car-gallery'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
                $image_name = 'uploads/custom-images/'.$image_name;
                $manager = new ImageManager(['driver' => 'gd']);
                $img = $manager->make($image);

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
        }

        if($request->video_image){
            $old_image = $car->video_image;
            $image_name = 'car-video-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/custom-images/'.$image_name;
            Image::make($request->video_image)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $car->video_image = $image_name;
            $car->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        $car->agent_id = $request->agent_id;
        $car->brand_id = $request->brand_id;
        $car->city_id = $request->city_id;
        $car->country_id = $request->country_id;
        $car->slug = $request->slug;
        $car->features = json_encode($request->features);
        $car->purpose = $request->purpose;
        $car->rent_period = null;
        $car->condition = $request->condition;
        $car->regular_price = $request->price;
        $car->offer_price = null;
        $car->video_id = $request->video_id;
        $car->google_map = null;
        $car->body_type = $request->body_type;
        $car->engine_size = $request->engine_size;
        $car->drive = $request->drive;
        $car->interior_color = $request->interior_color;
        $car->exterior_color = $request->exterior_color;
        $car->year = $request->year;
        $car->mileage = $request->mileage;
        $car->number_of_owner = $request->number_of_owner;
        $car->fuel_type = $request->fuel_type;
        $car->transmission = $request->transmission;
        $car->car_model = $request->car_model;
        $authUser = Auth::guard('web')->user();
        $car->seller_type = ($authUser && $authUser->is_dealer) ? 'Dealer' : 'Personal';
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

        $car_translate = CarTranslation::findOrFail($request->translate_id);
        $car_translate->title = $request->title;
        $car_translate->description = $request->description;
        $car_translate->video_description = $request->video_description;
        $car_translate->address = null;
        $car_translate->seo_title = $request->seo_title ? $request->seo_title : $request->title;
        $car_translate->seo_description = $request->seo_description ? $request->seo_description : $request->title;
        $car_translate->save();

        $notification= trans('translate.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $car = Car::findOrFail($id);
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
                $image = $manager->make($image);

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

        if($old_image){
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        $gallery->delete();

        $notification=  trans('translate.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }
}
