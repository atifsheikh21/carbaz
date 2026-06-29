<?php

namespace Modules\Car\Http\Controllers\Frontend;

use App\Models\CarPart;
use App\Models\CarPartGallery;
use App\Models\IndividualAdPayment;
use App\Models\CarPartTranslation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\GeneralSetting\Entities\Setting;
use Modules\Brand\Entities\Brand;
use Modules\Car\Entities\Car;
use Modules\Car\Http\Requests\CarPartRequest;
use Modules\City\Entities\City;
use Modules\Country\Entities\Country;
use Modules\Language\Entities\Language;
use Modules\Brand\Entities\BrandTranslation;
use Modules\Subscription\Entities\SubscriptionHistory;

class FrontendCarPartController extends Controller
{
    private function getIrelandMakerCatalog(): array
    {
        return [
            'audi' => ['label' => 'Audi', 'models' => ['A1', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'Q2', 'Q3', 'Q5', 'Q7', 'Q8', 'TT', 'e-tron']],
            'alfa-romeo' => ['label' => 'Alfa Romeo', 'models' => ['Giulia', 'Giulietta', 'Mito', 'Stelvio', 'Tonale']],
            'aston-martin' => ['label' => 'Aston Martin', 'models' => ['DB11', 'DB12', 'DBS', 'V8 Vantage', 'Vantage', 'DBX']],
            'bentley' => ['label' => 'Bentley', 'models' => ['Bentayga', 'Continental', 'Flying Spur', 'Mulsanne']],
            'bmw' => ['label' => 'BMW', 'models' => ['1 Series', '2 Series', '3 Series', '4 Series', '5 Series', '6 Series', '7 Series', '8 Series', 'X1', 'X2', 'X3', 'X4', 'X5', 'X6', 'X7', 'Z4', 'i3', 'i4', 'i5', 'i7', 'iX']],
            'byd' => ['label' => 'BYD', 'models' => ['Atto 3', 'Dolphin', 'Seal', 'Seal U', 'Han', 'Tang']],
            'chevrolet' => ['label' => 'Chevrolet', 'models' => ['Aveo', 'Camaro', 'Captiva', 'Cruze', 'Kalos', 'Lacetti', 'Orlando', 'Spark', 'Trax']],
            'chrysler' => ['label' => 'Chrysler', 'models' => ['300C', 'Grand Voyager', 'Pacifica', 'Voyager']],
            'citroen' => ['label' => 'Citroen', 'models' => ['Berlingo', 'C1', 'C3', 'C3 Aircross', 'C4', 'C4 Cactus', 'C4 Picasso', 'C5', 'C5 Aircross', 'Dispatch', 'DS3', 'Grand C4 Picasso', 'Relay']],
            'cupra' => ['label' => 'Cupra', 'models' => ['Ateca', 'Born', 'Formentor', 'Leon', 'Tavascan']],
            'dacia' => ['label' => 'Dacia', 'models' => ['Duster', 'Jogger', 'Logan', 'Sandero', 'Spring']],
            'daewoo' => ['label' => 'Daewoo', 'models' => ['Kalos', 'Lacetti', 'Matiz']],
            'daihatsu' => ['label' => 'Daihatsu', 'models' => ['Charade', 'Copen', 'Fourtrak', 'Sirion', 'Terios']],
            'dodge' => ['label' => 'Dodge', 'models' => ['Caliber', 'Challenger', 'Charger', 'Journey', 'Nitro', 'RAM']],
            'ds' => ['label' => 'DS', 'models' => ['DS 3', 'DS 4', 'DS 5', 'DS 7', 'DS 9']],
            'fiat' => ['label' => 'Fiat', 'models' => ['500', '500L', '500X', 'Bravo', 'Doblo', 'Ducato', 'Fiorino', 'Grande Punto', 'Panda', 'Punto', 'Tipo']],
            'ford' => ['label' => 'Ford', 'models' => ['B-Max', 'C-Max', 'EcoSport', 'Edge', 'Explorer', 'Fiesta', 'Focus', 'Fusion', 'Galaxy', 'Kuga', 'Ka', 'Mondeo', 'Mustang', 'Puma', 'Ranger', 'S-Max', 'Tourneo', 'Transit']],
            'honda' => ['label' => 'Honda', 'models' => ['Accord', 'Civic', 'CR-V', 'FR-V', 'HR-V', 'Insight', 'Jazz', 'NSX', 'S2000', 'ZR-V']],
            'hyundai' => ['label' => 'Hyundai', 'models' => ['Bayon', 'i10', 'i20', 'i30', 'Ioniq', 'Ioniq 5', 'Ioniq 6', 'ix20', 'ix35', 'Kona', 'Santa Fe', 'Tucson']],
            'jaguar' => ['label' => 'Jaguar', 'models' => ['E-Pace', 'F-Pace', 'F-Type', 'I-Pace', 'XE', 'XF', 'XJ']],
            'jeep' => ['label' => 'Jeep', 'models' => ['Avenger', 'Cherokee', 'Compass', 'Grand Cherokee', 'Renegade', 'Wrangler']],
            'kia' => ['label' => 'Kia', 'models' => ['Carens', 'Ceed', 'EV3', 'EV6', 'EV9', 'Niro', 'Optima', 'Picanto', 'ProCeed', 'Rio', 'Sorento', 'Soul', 'Sportage', 'Stonic', 'Venga', 'XCeed']],
            'land-rover' => ['label' => 'Land Rover', 'models' => ['Defender', 'Discovery', 'Discovery Sport', 'Freelander', 'Range Rover', 'Range Rover Evoque', 'Range Rover Sport', 'Range Rover Velar']],
            'lexus' => ['label' => 'Lexus', 'models' => ['CT', 'ES', 'GS', 'IS', 'LC', 'LS', 'NX', 'RX', 'UX']],
            'mazda' => ['label' => 'Mazda', 'models' => ['2', '3', '5', '6', 'CX-3', 'CX-30', 'CX-5', 'CX-60', 'CX-7', 'CX-9', 'MX-5', 'MX-30']],
            'mercedes-benz' => ['label' => 'Mercedes-Benz', 'models' => ['A-Class', 'B-Class', 'C-Class', 'CLA', 'CLC', 'CLK', 'CLS', 'E-Class', 'EQA', 'EQB', 'EQC', 'EQE', 'EQS', 'G-Class', 'GLA', 'GLB', 'GLC', 'GLE', 'GLS', 'SL', 'S-Class', 'V-Class', 'Sprinter']],
            'mg' => ['label' => 'MG', 'models' => ['HS', 'MG3', 'MG4', 'MG5', 'Marvel R', 'ZS']],
            'mini' => ['label' => 'MINI', 'models' => ['Clubman', 'Countryman', 'Cooper', 'Convertible', 'Paceman']],
            'mitsubishi' => ['label' => 'Mitsubishi', 'models' => ['ASX', 'Colt', 'Eclipse Cross', 'L200', 'Lancer', 'Outlander', 'Pajero', 'Space Star']],
            'nissan' => ['label' => 'Nissan', 'models' => ['Almera', 'Ariya', 'Juke', 'Leaf', 'Micra', 'Navara', 'Note', 'NV200', 'Pathfinder', 'Pulsar', 'Qashqai', 'X-Trail']],
            'opel' => ['label' => 'Opel', 'models' => ['Adam', 'Astra', 'Corsa', 'Crossland', 'Grandland', 'Insignia', 'Meriva', 'Mokka', 'Vivaro', 'Zafira']],
            'peugeot' => ['label' => 'Peugeot', 'models' => ['107', '108', '2008', '208', '3008', '301', '308', '407', '408', '5008', '508', 'Boxer', 'Expert', 'Partner', 'RCZ']],
            'polestar' => ['label' => 'Polestar', 'models' => ['Polestar 1', 'Polestar 2', 'Polestar 3', 'Polestar 4']],
            'porsche' => ['label' => 'Porsche', 'models' => ['718', '911', 'Cayenne', 'Cayman', 'Macan', 'Panamera', 'Taycan']],
            'renault' => ['label' => 'Renault', 'models' => ['Arkana', 'Austral', 'Captur', 'Clio', 'Espace', 'Fluence', 'Grand Scenic', 'Kadjar', 'Kangoo', 'Koleos', 'Laguna', 'Megane', 'Scenic', 'Symbioz', 'Trafic', 'Twingo', 'Zoe']],
            'rover' => ['label' => 'Rover', 'models' => ['25', '45', '75', 'Mini']],
            'saab' => ['label' => 'Saab', 'models' => ['9-3', '9-5', '900']],
            'seat' => ['label' => 'SEAT', 'models' => ['Alhambra', 'Arona', 'Ateca', 'Ibiza', 'Leon', 'Mii', 'Toledo']],
            'skoda' => ['label' => 'Skoda', 'models' => ['Citigo', 'Enyaq', 'Fabia', 'Kamiq', 'Karoq', 'Kodiaq', 'Octavia', 'Rapid', 'Scala', 'Superb', 'Yeti']],
            'smart' => ['label' => 'Smart', 'models' => ['ForFour', 'ForTwo', '#1', '#3']],
            'subaru' => ['label' => 'Subaru', 'models' => ['BRZ', 'Forester', 'Impreza', 'Legacy', 'Levorg', 'Outback', 'XV']],
            'suzuki' => ['label' => 'Suzuki', 'models' => ['Across', 'Alto', 'Baleno', 'Grand Vitara', 'Ignis', 'Jimny', 'S-Cross', 'Splash', 'Swift', 'SX4', 'Vitara']],
            'tesla' => ['label' => 'Tesla', 'models' => ['Model 3', 'Model S', 'Model X', 'Model Y']],
            'toyota' => ['label' => 'Toyota', 'models' => ['Auris', 'Avensis', 'Aygo', 'bZ4X', 'C-HR', 'Camry', 'Celica', 'Corolla', 'GT86', 'Hilux', 'Land Cruiser', 'Prius', 'Proace', 'RAV4', 'Verso', 'Yaris']],
            'vauxhall' => ['label' => 'Vauxhall', 'models' => ['Astra', 'Corsa', 'Crossland', 'Grandland', 'Insignia', 'Meriva', 'Mokka', 'Movano', 'Vivaro', 'Zafira']],
            'volkswagen' => ['label' => 'Volkswagen', 'models' => ['Amarok', 'Arteon', 'Beetle', 'Caddy', 'California', 'Crafter', 'Golf', 'ID.3', 'ID.4', 'ID.5', 'ID.7', 'Jetta', 'Passat', 'Polo', 'Sharan', 'T-Cross', 'T-Roc', 'Taigo', 'Tiguan', 'Touareg', 'Touran', 'Transporter', 'Up!']],
            'volvo' => ['label' => 'Volvo', 'models' => ['C30', 'C40', 'S40', 'S60', 'S80', 'S90', 'V40', 'V60', 'V90', 'XC40', 'XC60', 'XC70', 'XC90', 'EX30', 'EX40', 'EX90']],
        ];
    }

    public function index(Request $request)
    {
        $user = Auth::guard('web')->user();

        if ($user?->is_dealer && !(bool) $user?->is_part_seller) {
            $notification = trans('translate.Access denied');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];
            return redirect()->route('user.select-car-purpose')->with($notification);
        }

        $status = $request->get('status', 'all');

        $today = date('Y-m-d');

        $baseQuery = CarPart::where('agent_id', $user->id);

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

        $carPartsQuery = clone $baseQuery;
        if ($status === 'active') {
            $carPartsQuery
                ->where('approved_by_admin', 'approved')
                ->where('status', 'enable')
                ->where(function ($q) use ($today) {
                    $q->whereNull('expired_date')->orWhere('expired_date', '>=', $today);
                });
        } elseif ($status === 'inactive') {
            $carPartsQuery->where(function ($q) use ($today) {
                $q->where('approved_by_admin', '!=', 'approved')
                    ->orWhere('status', '!=', 'enable')
                    ->orWhere(function ($q2) use ($today) {
                        $q2->whereNotNull('expired_date')->where('expired_date', '<', $today);
                    });
            });
        }

        $carParts = $carPartsQuery
            ->with(['brand.front_translate', 'brand.translate', 'translations'])
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('car::frontend.car_parts.index', [
            'carParts' => $carParts,
            'user' => $user,
            'status' => $status,
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
            'today' => $today,
        ]);
    }

    public function toggleStatus(Request $request, CarPart $carPart): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if (!$user || (int) $carPart->agent_id !== (int) $user->id) {
            abort(403);
        }

        $today = date('Y-m-d');
        $isActive = $carPart->approved_by_admin === 'approved'
            && $carPart->status === 'enable'
            && (!$carPart->expired_date || $carPart->expired_date->format('Y-m-d') >= $today);

        if ($isActive) {
            $carPart->status = 'disable';
            $carPart->save();

            $notification = trans('translate.Updated Successfully');
            $notification = ['messege' => $notification, 'alert-type' => 'success'];
            return redirect()->back()->with($notification);
        }

        if ($user->is_dealer) {
            $setting = Setting::first();
            $feeFreeModeEnabled = $setting && $setting->fee_free_mode == 'enable';

            if ($feeFreeModeEnabled) {
                $carPart->expired_date = date('Y-m-d', strtotime('+30 days'));
                $carPart->status = 'enable';
                $carPart->save();

                $notification = trans('translate.Updated Successfully');
                $notification = ['messege' => $notification, 'alert-type' => 'success'];
                return redirect()->back()->with($notification);
            }

            $activePlan = SubscriptionHistory::where('user_id', $user->id)
                ->where('status', 'active')
                ->latest()
                ->first();

            if (!$activePlan) {
                $notification = trans('translate.Please enroll first');
                $notification = ['messege' => $notification, 'alert-type' => 'error'];
                return redirect()->route('pricing-plan')->with($notification);
            }

            if ($activePlan->expiration_date !== 'lifetime' && $today > $activePlan->expiration_date) {
                $notification = trans('translate.Your plan is expired, please renew or re-order');
                $notification = ['messege' => $notification, 'alert-type' => 'error'];
                return redirect()->route('pricing-plan')->with($notification);
            }

            $perAdExpiry = date('Y-m-d', strtotime('+30 days'));
            if ($activePlan->expiration_date === 'lifetime') {
                $carPart->expired_date = $perAdExpiry;
            } else {
                $carPart->expired_date = $activePlan->expiration_date < $perAdExpiry ? $activePlan->expiration_date : $perAdExpiry;
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
                $notification = trans('translate.Please complete payment to post your ad');
                $notification = ['messege' => $notification, 'alert-type' => 'error'];
                return redirect()->back()->with($notification);
            }

            $pendingPayment->consumed_at = now();
            $pendingPayment->save();

            $carPart->expired_date = date('Y-m-d', strtotime('+30 days'));
        }

        $carPart->status = 'enable';
        $carPart->save();

        $notification = trans('translate.Updated Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];
        return redirect()->back()->with($notification);
    }

    public function create()
    {
        $user = Auth::guard('web')->user();

        if ($user?->is_dealer && !(bool) $user?->is_part_seller) {
            $notification = trans('translate.Access denied');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];
            return redirect()->route('user.select-car-purpose')->with($notification);
        }

        $setting = Setting::first();
        $feeFreeModeEnabled = $setting && $setting->fee_free_mode == 'enable';

        if ($user && $user->is_dealer) {
            if (!$feeFreeModeEnabled) {
                $activePlan = SubscriptionHistory::where('user_id', $user->id)
                    ->where('status', 'active')
                    ->latest()
                    ->first();

                $today = date('Y-m-d');
                if (!$activePlan) {
                    $notification = trans('translate.Please enroll first');
                    $notification = ['messege' => $notification, 'alert-type' => 'error'];
                    return redirect()->route('pricing-plan')->with($notification);
                }

                if ($activePlan->expiration_date !== 'lifetime' && $today > $activePlan->expiration_date) {
                    $notification = trans('translate.Your plan is expired, please renew or re-order');
                    $notification = ['messege' => $notification, 'alert-type' => 'error'];
                    return redirect()->route('pricing-plan')->with($notification);
                }

                $maxPart = (int) ($activePlan->max_car_part ?? 0);
                if ($maxPart > 0) {
                    $currentActiveCount = CarPart::where('agent_id', $user->id)
                        ->where('approved_by_admin', 'approved')
                        ->where('status', 'enable')
                        ->where(function ($q) use ($today) {
                            $q->whereNull('expired_date')->orWhere('expired_date', '>=', $today);
                        })
                        ->count();

                    if ($currentActiveCount >= $maxPart) {
                        $notification = trans('translate.Your car limitation has exceeded');
                        $notification = ['messege' => $notification, 'alert-type' => 'error'];
                        return redirect()->route('user.select-car-purpose')->with($notification);
                    }
                }
            }
        }

        if ($user && !$user->is_dealer) {

            $pendingIndividualPaymentQuery = IndividualAdPayment::where('user_id', $user->id)
                ->where('status', 'success')
                ->whereNull('consumed_at');

            if (!$feeFreeModeEnabled) {
                $pendingIndividualPaymentQuery->where('payment_method', '!=', 'Free');
            }

            $pendingIndividualPayment = $pendingIndividualPaymentQuery
                ->orderBy('id', 'asc')
                ->first();

            if (!$pendingIndividualPayment) {
                if ($feeFreeModeEnabled) {
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
                } else {
                    $notification = trans('translate.Please complete payment to post your ad');
                    $notification = ['messege' => $notification, 'alert-type' => 'error'];
                    return redirect()->route('user.select-car-purpose')->with($notification);
                }
            }
        }

        $makerOptions = $this->getMakerOptions();
        $ireland = Country::whereRaw('LOWER(name) = ?', ['ireland'])->first();
        $cities = collect();
        if ($ireland) {
            $cities = City::with('translate')->where('country_id', $ireland->id)->get();
        }

        return view('car::frontend.car_parts.create', [
            'makerOptions' => $makerOptions,
            'brandModelsMap' => $this->getBrandModelsMap(),
            'ireland' => $ireland,
            'cities' => $cities,
            'setting' => $setting,
            'feeFreeModeEnabled' => $feeFreeModeEnabled,
        ]);
    }

    public function store(CarPartRequest $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if ($user?->is_dealer && !(bool) $user?->is_part_seller) {
            $notification = trans('translate.Access denied');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];
            return redirect()->route('user.select-car-purpose')->with($notification);
        }

        $pendingIndividualPayment = null;
        if ($user && !$user->is_dealer) {
            $setting = Setting::first();
            $feeFreeModeEnabled = $setting && $setting->fee_free_mode == 'enable';

            $pendingIndividualPaymentQuery = IndividualAdPayment::where('user_id', $user->id)
                ->where('status', 'success')
                ->whereNull('consumed_at');

            if (!$feeFreeModeEnabled) {
                $pendingIndividualPaymentQuery->where('payment_method', '!=', 'Free');
            }

            $pendingIndividualPayment = $pendingIndividualPaymentQuery
                ->orderBy('id', 'asc')
                ->first();

            if (!$pendingIndividualPayment) {
                if ($feeFreeModeEnabled) {
                    $pendingIndividualPayment = IndividualAdPayment::create([
                        'user_id' => $user->id,
                        'car_id' => null,
                        'amount' => 0,
                        'currency' => 'EUR',
                        'payment_method' => 'Free',
                        'status' => 'success',
                        'transaction_id' => 'free_period',
                        'consumed_at' => null,
                    ]);
                } else {
                    $notification = trans('translate.Please complete payment to post your ad');
                    $notification = ['messege' => $notification, 'alert-type' => 'error'];
                    return redirect()->route('user.select-car-purpose')->with($notification);
                }
            }
        }

        $carPart = new CarPart();
        $carPart->agent_id = $user->id;
        $resolvedBrandId = $this->resolveBrandIdFromSelection($request->brand_id);
        $carPart->brand_id = ($resolvedBrandId && $resolvedBrandId > 0) ? $resolvedBrandId : null;
        $carPart->city_id = $request->city_id;
        $carPart->slug = $this->generateUniqueSlug($request->title, $user->id);
        $carPart->condition = $request->condition;
        $carPart->regular_price = $request->regular_price;
        $carPart->offer_price = null;
        $carPart->part_number = $request->part_number;
        $carPart->car_model = $request->car_model;
        $carPart->from_year = $request->from_year;
        $carPart->to_year = $request->to_year;
        if ($user && $user->is_dealer) {
            $carPart->warranty_months = $request->warranty_months;
        } else {
            $carPart->warranty_months = null;
        }

        if ($user && $user->is_dealer) {
            $setting = Setting::first();
            $feeFreeModeEnabled = $setting && $setting->fee_free_mode == 'enable';

            if ($feeFreeModeEnabled) {
                $carPart->expired_date = date('Y-m-d', strtotime('+30 days'));
            } else {
                $today = date('Y-m-d');
                $activePlan = SubscriptionHistory::where('user_id', $user->id)
                    ->where('status', 'active')
                    ->latest()
                    ->first();

                if (!$activePlan) {
                    $notification = trans('translate.Please enroll first');
                    $notification = ['messege' => $notification, 'alert-type' => 'error'];
                    return redirect()->route('pricing-plan')->with($notification);
                }

                if ($activePlan->expiration_date !== 'lifetime' && $today > $activePlan->expiration_date) {
                    $notification = trans('translate.Your plan is expired, please renew or re-order');
                    $notification = ['messege' => $notification, 'alert-type' => 'error'];
                    return redirect()->route('pricing-plan')->with($notification);
                }

                $maxPart = (int) ($activePlan->max_car_part ?? 0);
                if ($maxPart > 0) {
                    $currentActiveCount = CarPart::where('agent_id', $user->id)
                        ->where('approved_by_admin', 'approved')
                        ->where('status', 'enable')
                        ->where(function ($q) use ($today) {
                            $q->whereNull('expired_date')->orWhere('expired_date', '>=', $today);
                        })
                        ->count();

                    if ($currentActiveCount >= $maxPart) {
                        $notification = trans('translate.Your car limitation has exceeded');
                        $notification = ['messege' => $notification, 'alert-type' => 'error'];
                        return redirect()->route('user.select-car-purpose')->with($notification);
                    }
                }

                $perAdExpiry = date('Y-m-d', strtotime('+30 days'));
                if ($activePlan->expiration_date === 'lifetime') {
                    $carPart->expired_date = $perAdExpiry;
                } else {
                    $carPart->expired_date = $activePlan->expiration_date < $perAdExpiry ? $activePlan->expiration_date : $perAdExpiry;
                }
            }
        } else {
            $carPart->expired_date = date('Y-m-d', strtotime('+30 days'));
        }

        $carPart->save();

        $uploadedImages = $request->file('images', []);
        foreach ($uploadedImages as $index => $image) {
            if (!$image) {
                continue;
            }

            $imagePath = uploadFile($image, 'uploads/custom-images');

            if ($index === 0 && empty($carPart->thumb_image)) {
                $carPart->thumb_image = $imagePath;
                $carPart->save();
            }

            $gallery = new CarPartGallery();
            $gallery->car_part_id = $carPart->id;
            $gallery->image = $imagePath;
            $gallery->save();
        }

        $languages = Language::all();
        foreach ($languages as $language) {
            $t = new CarPartTranslation();
            $t->car_part_id = $carPart->id;
            $t->lang_code = $language->lang_code;
            $t->title = $request->title;
            $t->description = $request->description;
            $t->seo_title = $request->title;
            $t->seo_description = $request->title;
            $t->save();
        }

        if ($pendingIndividualPayment) {
            $pendingIndividualPayment->consumed_at = now();
            $pendingIndividualPayment->save();
        }

        $notification = trans('translate.Created Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->route('user.car-part.index')->with($notification);
    }

    public function edit(int $id)
    {
        $user = Auth::guard('web')->user();

        if ($user?->is_dealer && !(bool) $user?->is_part_seller) {
            $notification = trans('translate.Access denied');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];
            return redirect()->route('user.select-car-purpose')->with($notification);
        }

        $carPart = CarPart::with('galleries')->where('agent_id', $user->id)->findOrFail($id);

        $makerOptions = $this->getMakerOptions();
        $ireland = Country::whereRaw('LOWER(name) = ?', ['ireland'])->first();
        $cities = collect();
        if ($ireland) {
            $cities = City::with('translate')->where('country_id', $ireland->id)->get();
        }
        $translation = CarPartTranslation::where('car_part_id', $carPart->id)->where('lang_code', admin_lang())->first();
        $selectedBrandOption = old('brand_id');
        if ($selectedBrandOption === null && $carPart->brand) {
            $selectedBrandOption = trim((string) ($carPart->brand->slug ?? ''));
            if ($selectedBrandOption === '') {
                $selectedBrandOption = Str::slug((string) $carPart->brand->name);
            }
        }

        return view('car::frontend.car_parts.edit', [
            'carPart' => $carPart,
            'makerOptions' => $makerOptions,
            'brandModelsMap' => $this->getBrandModelsMap(),
            'selectedBrandOption' => $selectedBrandOption,
            'translation' => $translation,
            'ireland' => $ireland,
            'cities' => $cities,
        ]);
    }

    public function update(CarPartRequest $request, int $id): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if ($user?->is_dealer && !(bool) $user?->is_part_seller) {
            $notification = trans('translate.Access denied');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];
            return redirect()->route('user.select-car-purpose')->with($notification);
        }

        $carPart = CarPart::withCount('galleries')->where('agent_id', $user->id)->findOrFail($id);

        $uploadedImageCount = count(array_filter($request->file('images', [])));
        if (($carPart->galleries_count + $uploadedImageCount) > 8) {
            return redirect()
                ->back()
                ->withErrors(['images' => __('Maximum 8 images allowed')])
                ->withInput();
        }

        $resolvedBrandId = $this->resolveBrandIdFromSelection($request->brand_id);
        $carPart->brand_id = ($resolvedBrandId && $resolvedBrandId > 0) ? $resolvedBrandId : null;
        $carPart->city_id = $request->city_id;
        $carPart->slug = $this->generateUniqueSlug($request->title, $user->id, $carPart->id);
        $carPart->condition = $request->condition;
        $carPart->regular_price = $request->regular_price;
        $carPart->offer_price = null;
        $carPart->part_number = $request->part_number;
        $carPart->car_model = $request->car_model;
        $carPart->from_year = $request->from_year;
        $carPart->to_year = $request->to_year;
        if ($user && $user->is_dealer) {
            $carPart->warranty_months = $request->warranty_months;
        } else {
            $carPart->warranty_months = null;
        }

        $carPart->save();

        $uploadedImages = $request->file('images', []);
        foreach ($uploadedImages as $index => $image) {
            if (!$image) {
                continue;
            }

            $imagePath = uploadFile($image, 'uploads/custom-images');

            if ($index === 0 && empty($carPart->thumb_image)) {
                $carPart->thumb_image = $imagePath;
                $carPart->save();
            }

            $gallery = new CarPartGallery();
            $gallery->car_part_id = $carPart->id;
            $gallery->image = $imagePath;
            $gallery->save();
        }

        $translation = CarPartTranslation::firstOrNew([
            'car_part_id' => $carPart->id,
            'lang_code' => admin_lang(),
        ]);
        $translation->title = $request->title;
        $translation->description = $request->description;
        $translation->seo_title = $request->title;
        $translation->seo_description = $request->title;
        $translation->save();

        $notification = trans('translate.Updated Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->route('user.car-part.index')->with($notification);
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = Auth::guard('web')->user();
        $carPart = CarPart::where('agent_id', $user->id)->findOrFail($id);
        $carPart->delete();

        $notification = trans('translate.Deleted Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function deleteGallery(int $id): RedirectResponse
    {
        $user = Auth::guard('web')->user();
        $gallery = CarPartGallery::findOrFail($id);
        $carPart = CarPart::findOrFail($gallery->car_part_id);

        if ((int) $carPart->agent_id !== (int) $user->id) {
            abort(403);
        }

        $oldImage = $gallery->image;

        if ($oldImage && File::exists(public_path($oldImage))) {
            File::delete(public_path($oldImage));
        }

        $gallery->delete();

        $thumbExistsInGalleries = !empty($carPart->thumb_image)
            && CarPartGallery::where('car_part_id', $carPart->id)->where('image', $carPart->thumb_image)->exists();

        if ($carPart->thumb_image === $oldImage || !$thumbExistsInGalleries) {
            $nextThumb = CarPartGallery::where('car_part_id', $carPart->id)->oldest('id')->value('image');
            $carPart->thumb_image = $nextThumb ?: '';
            $carPart->save();
        }

        $notification = trans('translate.Delete Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    private function generateUniqueSlug(string $title, int $userId, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        if ($baseSlug === '') {
            $baseSlug = 'car-part';
        }

        $slug = $baseSlug . '-' . $userId;
        $counter = 1;

        while (CarPart::when($ignoreId, function ($query) use ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        })->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $userId . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function getMakerOptions(): array
    {
        $catalog = $this->getIrelandMakerCatalog();
        $options = [];

        foreach ($catalog as $slug => $item) {
            $options[$slug] = $item['label'];
        }

        $brands = Brand::where('status', 'enable')->get();
        foreach ($brands as $brand) {
            $name = trim((string) $brand->name);
            $slug = trim((string) ($brand->slug ?? ''));
            if ($slug === '') {
                $slug = Str::slug($name);
            }
            if ($slug === 'alfa-remoe') {
                $slug = 'alfa-romeo';
                $name = 'Alfa Romeo';
            }
            if ($slug === '' || isset($options[$slug])) {
                continue;
            }

            $options[$slug] = $name;
        }

        asort($options, SORT_NATURAL | SORT_FLAG_CASE);

        return $options;
    }

    private function resolveBrandIdFromSelection($selection): ?int
    {
        $selection = trim((string) $selection);
        if ($selection === '') {
            return null;
        }

        if (ctype_digit($selection)) {
            $brand = Brand::find((int) $selection);
            return $brand ? (int) $brand->id : null;
        }

        $brand = Brand::query()
            ->where('status', 'enable')
            ->where('slug', $selection)
            ->first();

        if (!$brand) {
            $brand = Brand::query()
                ->where('status', 'enable')
                ->get()
                ->first(function ($item) use ($selection) {
                    $slug = trim((string) ($item->slug ?? ''));
                    if ($slug === '') {
                        $slug = Str::slug((string) $item->name);
                    }
                    return $slug === $selection;
                });
        }

        if ($brand) {
            return (int) $brand->id;
        }

        $label = $this->getMakerOptions()[$selection] ?? null;
        if (!$label) {
            return null;
        }

        $brand = new Brand();
        $brand->slug = $selection;
        $brand->status = 'enable';
        $brand->save();

        if (empty($brand->id) || (int) $brand->id <= 0) {
            return null;
        }

        foreach (Language::all() as $language) {
            $translation = new BrandTranslation();
            $translation->brand_id = $brand->id;
            $translation->lang_code = $language->lang_code;
            $translation->name = $label;
            $translation->save();
        }

        return (int) $brand->id;
    }

    private function getBrandModelsMap(): array
    {
        $catalog = $this->getIrelandMakerCatalog();
        $map = [];

        foreach ($catalog as $slug => $item) {
            $map[$slug] = $item['models'];
        }

        $rows = Car::query()
            ->with('brand')
            ->select('id', 'brand_id', 'car_model')
            ->whereNotNull('brand_id')
            ->whereNotNull('car_model')
            ->where('car_model', '!=', '')
            ->get();

        foreach ($rows as $row) {
            $brandName = trim((string) optional($row->brand)->name);
            $brandSlug = $brandName !== '' ? Str::slug($brandName) : null;
            $modelName = trim((string) $row->car_model);
            if (!$brandSlug || $modelName === '') {
                continue;
            }

            $map[$brandSlug] ??= [];
            if (!in_array($modelName, $map[$brandSlug], true)) {
                $map[$brandSlug][] = $modelName;
            }
        }

        foreach ($map as &$models) {
            sort($models, SORT_NATURAL | SORT_FLAG_CASE);
        }
        unset($models);

        return $map;
    }
}
