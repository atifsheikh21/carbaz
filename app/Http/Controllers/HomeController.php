<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CarPart;
use App\Models\CarPartRequest;

use App\Models\Review;
use App\Rules\Captcha;
use App\Models\AdsBanner;
use App\Helpers\MailHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Car\Entities\Car;
use Modules\Page\Entities\Faq;
use Modules\Blog\Entities\Blog;
use Modules\City\Entities\City;
use Modules\Brand\Entities\Brand;
use Modules\Page\Entities\AboutUs;
use Modules\Page\Entities\HomePage;
use Modules\Car\Entities\CarGallery;
use Modules\Page\Entities\ContactUs;
use Modules\Country\Entities\Country;
use Modules\Feature\Entities\Feature;
use Modules\Page\Entities\CustomPage;
use Modules\Blog\Entities\BlogComment;
use Modules\Blog\Entities\BlogCategory;
use Modules\Language\Entities\Language;
use Modules\Page\Entities\PrivacyPolicy;
use Modules\Page\Entities\TermAndCondition;
use Modules\GeneralSetting\Entities\Setting;
use Modules\Testimonial\Entities\Testimonial;
use Modules\Currency\app\Models\MultiCurrency;

use Modules\GeneralSetting\Entities\SeoSetting;
use Modules\GeneralSetting\Entities\EmailTemplate;

use Str, Mail, Hash, Auth, Session,Config,Artisan;

use Modules\Subscription\Entities\SubscriptionPlan;

use Modules\ContactMessage\Emails\SendContactMessage;
use Modules\ContactMessage\Http\Requests\ContactMessageRequest;

class HomeController extends Controller
{

    protected function getIrelandMakerCatalog(): array
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

    protected function getEnabledBrandSlugMap(): array
    {
        $map = [];
        $brands = Brand::where('status', 'enable')->get();

        foreach ($brands as $brand) {
            $slug = Str::slug((string) $brand->name);
            if ($slug === '') {
                continue;
            }

            $map[$slug] ??= [];
            $map[$slug][] = (int) $brand->id;
        }

        return $map;
    }

    protected function getMakerOptions(): array
    {
        $catalog = $this->getIrelandMakerCatalog();
        $options = [];

        foreach ($catalog as $slug => $item) {
            $options[$slug] = $item['label'];
        }

        $brands = Brand::where('status', 'enable')->get();
        foreach ($brands as $brand) {
            $name = trim((string) $brand->name);
            $slug = Str::slug($name);
            if ($slug === '' || isset($options[$slug])) {
                continue;
            }
            $options[$slug] = $name;
        }

        asort($options, SORT_NATURAL | SORT_FLAG_CASE);

        return $options;
    }

    protected function resolveBrandIdsForSelection($selection): array
    {
        $selection = trim((string) $selection);
        if ($selection === '') {
            return [];
        }

        if (ctype_digit($selection)) {
            return [(int) $selection];
        }

        $brandSlugMap = $this->getEnabledBrandSlugMap();

        return $brandSlugMap[$selection] ?? [];
    }

    protected function getCarBrandModelsMap(): array
    {
        $catalog = $this->getIrelandMakerCatalog();
        $map = [];
        foreach ($catalog as $slug => $item) {
            $map[$slug] = $item['models'];
        }

        $brandSlugMap = $this->getEnabledBrandSlugMap();
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
            if (!$brandSlug && !empty($brandSlugMap[(string) $row->brand_id])) {
                $brandSlug = (string) $row->brand_id;
            }
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

    protected function getPartBrandModelsMap(): array
    {
        $catalog = $this->getIrelandMakerCatalog();
        $map = [];
        foreach ($catalog as $slug => $item) {
            $map[$slug] = $item['models'];
        }

        $rows = CarPart::query()
            ->with('brand')
            ->select('id', 'brand_id', 'compatibility')
            ->whereNotNull('brand_id')
            ->whereNotNull('compatibility')
            ->where('compatibility', '!=', '')
            ->get();

        foreach ($rows as $row) {
            $brandName = trim((string) optional($row->brand)->name);
            $brandSlug = $brandName !== '' ? Str::slug($brandName) : null;
            $modelName = trim((string) $row->compatibility);
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

    public function landing(Request $request)
    {
        $brands = $this->getMakerOptions();
        $carBrandModels = $this->getCarBrandModelsMap();
        $partBrandModels = $this->getPartBrandModelsMap();

        return view('landing_search', [
            'brands' => $brands,
            'carBrandModels' => $carBrandModels,
            'partBrandModels' => $partBrandModels,
        ]);
    }


    public function index(Request $request){
        $setting = Setting::select('selected_theme')->first();
        if($setting && $setting->selected_theme == 'all_theme'){
            if($request->has('theme')){
                $theme = $request->theme;
                if($theme == 'one'){
                    Session::put('selected_theme', 'theme_one');
                }elseif($theme == 'two'){
                    Session::put('selected_theme', 'theme_two');
                }elseif($theme == 'three'){
                    Session::put('selected_theme', 'theme_three');
                }else{
                    if(!Session::has('selected_theme')){
                        Session::put('selected_theme', 'theme_one');
                    }
                }
            }else{
                Session::put('selected_theme', 'theme_one');
            }
        }else{
            if($setting && $setting->selected_theme == 'theme_one'){
                Session::put('selected_theme', 'theme_one');
            }elseif($setting && $setting->selected_theme == 'theme_two'){
                Session::put('selected_theme', 'theme_two');
            }elseif($setting && $setting->selected_theme == 'theme_three'){
                Session::put('selected_theme', 'theme_three');
            }else{
                Session::put('selected_theme', 'theme_one');
            }
        }

        $selected_theme = Session::get('selected_theme');
        $cache_lang = function_exists('front_lang') ? front_lang() : app()->getLocale();
        $cache_key_prefix = 'home.index.' . $selected_theme . '.' . $cache_lang;

        $seo_setting = Cache::remember($cache_key_prefix . '.seo_setting', 60, function () {
            return SeoSetting::where('id', 1)->first();
        });

        $homepage = Cache::remember($cache_key_prefix . '.homepage', 60, function () {
            return HomePage::with('front_translate')->first();
        });

        $brands = Cache::remember($cache_key_prefix . '.brands', 300, function () {
            return Brand::where('status', 'enable')->get();
        });

        $used_cars = Cache::remember($cache_key_prefix . '.used_cars', 60, function () {
            return Car::with('dealer', 'brand')->where(function ($query) {
                $query->where('expired_date', null)
                    ->orWhere('expired_date', '>=', date('Y-m-d'));
            })->where(['condition' => 'Used', 'status' => 'enable', 'approved_by_admin' => 'approved'])->take(8)->get();
        });

        $new_cars = Cache::remember($cache_key_prefix . '.new_cars', 60, function () {
            return Car::with('dealer', 'brand')->where(function ($query) {
                $query->where('expired_date', null)
                    ->orWhere('expired_date', '>=', date('Y-m-d'));
            })->where(['condition' => 'New', 'status' => 'enable', 'approved_by_admin' => 'approved'])->take(8)->get();
        });

        $featured_cars = Cache::remember($cache_key_prefix . '.featured_cars', 60, function () {
            return Car::with('dealer', 'brand')->where(function ($query) {
                $query->where('expired_date', null)
                    ->orWhere('expired_date', '>=', date('Y-m-d'));
            })->where(['is_featured' => 'enable', 'status' => 'enable', 'approved_by_admin' => 'approved'])->take(6)->get();
        });

        $car_parts = Cache::remember($cache_key_prefix . '.car_parts', 60, function () {
            return CarPart::with('brand', 'translations')->where(function ($query) {
                $query->where('expired_date', null)
                    ->orWhere('expired_date', '>=', date('Y-m-d'));
            })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->orderBy('id', 'desc')->take(8)->get();
        });

        $testimonials = Cache::remember($cache_key_prefix . '.testimonials', 300, function () {
            return Testimonial::where('status', 'active')->orderBy('id','desc')->get();
        });

        $blogs = Cache::remember($cache_key_prefix . '.blogs', 300, function () {
            return Blog::where('status', 1)->orderBy('id','desc')->take(4)->get();
        });

        $forum_blogs = Cache::remember($cache_key_prefix . '.forum_blogs', 300, function () {
            return Blog::with(['category.front_translate', 'front_translate'])
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->take(12)
                ->get();
        });

        $forum_news = $forum_blogs->reject(function ($blog) {
            $categoryName = strtolower((string) optional($blog->category)->name);
            return str_contains($categoryName, 'review');
        })->take(5);

        $forum_reviews = $forum_blogs->filter(function ($blog) {
            $categoryName = strtolower((string) optional($blog->category)->name);
            return str_contains($categoryName, 'review');
        })->take(5);

        $forum_discussions = Cache::remember($cache_key_prefix . '.forum_discussions', 300, function () {
            return CarPartRequest::with('user')
                ->orderBy('id', 'desc')
                ->take(5)
                ->get();
        });

        $dealers = Cache::remember($cache_key_prefix . '.dealers', 60, function () {
            return User::where(['status' => 'enable' , 'is_banned' => 'no', 'is_dealer' => 1])
                ->where('email_verified_at', '!=', null)
                ->orderBy('id','desc')
                ->select('id','name','username','designation','image','status','is_banned','is_dealer', 'address', 'email', 'phone')
                ->paginate(12);
        });

        $subscription_plans = Cache::remember($cache_key_prefix . '.subscription_plans', 300, function () {
            return SubscriptionPlan::orderBy('serial', 'asc')->where('status', 'active')->get();
        });

        $home1_ads = Cache::remember($cache_key_prefix . '.home1_ads', 300, function () {
            return AdsBanner::where('position_key', 'home1_featured_car_sidebar')->first();
        });
        $home2_ads = Cache::remember($cache_key_prefix . '.home2_ads', 300, function () {
            return AdsBanner::where('position_key', 'home2_brand_sidebar')->first();
        });
        $home3_ads = Cache::remember($cache_key_prefix . '.home3_ads', 300, function () {
            return AdsBanner::where('position_key', 'home3_featured_sidebar')->first();
        });

        $cities = Cache::remember($cache_key_prefix . '.cities', 300, function () {
            return City::with('translate')->get();
        });

        $countries = Cache::remember($cache_key_prefix . '.countries', 300, function () {
            return Country::latest()->get();
        });


        if ($selected_theme == 'theme_one'){
            return view('index', [
                'seo_setting' => $seo_setting,
                'homepage' => $homepage,
                'brands' => $brands,
                'cities' => $cities,
                'countries' => $countries,
                'new_cars' => $new_cars,
                'used_cars' => $used_cars,
                'featured_cars' => $featured_cars,
                'car_parts' => $car_parts,
                'dealers' => $dealers,
                'testimonials' => $testimonials,
                'blogs' => $blogs,
                'forum_news' => $forum_news,
                'forum_reviews' => $forum_reviews,
                'forum_discussions' => $forum_discussions,
                'subscription_plans' => $subscription_plans,
                'home1_ads' => $home1_ads,
                'home2_ads' => $home2_ads,
                'home3_ads' => $home3_ads,

            ]);
        }elseif($selected_theme == 'theme_two'){
            return view('index2', [
                'seo_setting' => $seo_setting,
                'homepage' => $homepage,
                'brands' => $brands,
                'cities' => $cities,
                'countries' => $countries,
                'new_cars' => $new_cars,
                'used_cars' => $used_cars,
                'featured_cars' => $featured_cars,
                'car_parts' => $car_parts,
                'dealers' => $dealers,
                'testimonials' => $testimonials,
                'blogs' => $blogs,
                'forum_news' => $forum_news,
                'forum_reviews' => $forum_reviews,
                'forum_discussions' => $forum_discussions,
                'subscription_plans' => $subscription_plans,
                'home1_ads' => $home1_ads,
                'home2_ads' => $home2_ads,
                'home3_ads' => $home3_ads,
            ]);
        }elseif($selected_theme == 'theme_three'){
            return view('index3', [
                'seo_setting' => $seo_setting,
                'homepage' => $homepage,
                'brands' => $brands,
                'cities' => $cities,
                'countries' => $countries,
                'new_cars' => $new_cars,
                'used_cars' => $used_cars,
                'featured_cars' => $featured_cars,
                'car_parts' => $car_parts,
                'dealers' => $dealers,
                'testimonials' => $testimonials,
                'blogs' => $blogs,
                'forum_news' => $forum_news,
                'forum_reviews' => $forum_reviews,
                'forum_discussions' => $forum_discussions,
                'subscription_plans' => $subscription_plans,
                'home1_ads' => $home1_ads,
                'home2_ads' => $home2_ads,
                'home3_ads' => $home3_ads,
            ]);
        }else{
            return view('index', [
                'seo_setting' => $seo_setting,
                'homepage' => $homepage,
                'brands' => $brands,
                'cities' => $cities,
                'countries' => $countries,
                'new_cars' => $new_cars,
                'used_cars' => $used_cars,
                'featured_cars' => $featured_cars,
                'car_parts' => $car_parts,
                'dealers' => $dealers,
                'testimonials' => $testimonials,
                'blogs' => $blogs,
                'forum_news' => $forum_news,
                'forum_reviews' => $forum_reviews,
                'forum_discussions' => $forum_discussions,
                'subscription_plans' => $subscription_plans,
                'home1_ads' => $home1_ads,
                'home2_ads' => $home2_ads,
                'home3_ads' => $home3_ads,
            ]);
        }

    }

    public function about_us(){

        $seo_setting = SeoSetting::where('id', 3)->first();

        $about_us = AboutUs::first();

        $brands = Brand::where('status', 'enable')->get();

        $homepage = HomePage::first();

        $testimonials = Testimonial::where('status', 'active')->orderBy('id','desc')->get();

        return view('about_us')->with([
            'seo_setting' => $seo_setting,
            'about_us' => $about_us,
            'brands' => $brands,
            'homepage' => $homepage,
            'testimonials' => $testimonials,
        ]);
    }


    public function contact_us(){
        $seo_setting = SeoSetting::where('id', 4)->first();

        $contact_us = ContactUs::first();

        return view('contact_us')->with([
            'seo_setting' => $seo_setting,
            'contact_us' => $contact_us,
        ]);
    }

    public function terms_conditions(){
        $seo_setting = SeoSetting::where('id', 6)->first();

        $terms_condition = TermAndCondition::where('lang_code', Session::get('front_lang'))->first();

        return view('terms_conditions')->with([
            'seo_setting' => $seo_setting,
            'terms_condition' => $terms_condition,
        ]);
    }

    public function privacy_policy(){
        $seo_setting = SeoSetting::where('id', 7)->first();

        $privacy_policy = PrivacyPolicy::where('lang_code', Session::get('front_lang'))->first();

        return view('privacy_policy')->with([
            'seo_setting' => $seo_setting,
            'privacy_policy' => $privacy_policy,
        ]);
    }

    public function faq(){
        $seo_setting = SeoSetting::where('id', 5)->first();

        $faqs = Faq::latest()->get();

        $homepage = HomePage::first();

        return view('faq')->with([
            'seo_setting' => $seo_setting,
            'faqs' => $faqs,
            'homepage' => $homepage,
        ]);
    }

    public function blogs(Request $request){

        $seo_setting = SeoSetting::where('id', 2)->first();

        $blogs = Blog::with('author')->orderBy('id','desc')->where('status', 1);

        if($request->category){
            $blog_category = BlogCategory::where('slug', $request->category)->first();
            $blogs = $blogs->where('blog_category_id', $blog_category->id);
        }

        if($request->search){
            $blogs = $blogs->whereHas('translations', function ($query) use ($request) {
                            $query->where('title', 'like', '%' . $request->search . '%')
                                ->orWhere('description', 'like', '%' . $request->search . '%');
                        })
                        ->orWhere(function ($query) use ($request) {
                            $query->whereJsonContains('tags', ['value' => $request->search]);
                        });
        }

        $blogs = $blogs->paginate(9);

        $popular_blogs = Blog::where('is_popular', 'yes')->where('status', 1)->orderBy('id','desc')->get();

        $categories = BlogCategory::where('status', 1)->get();

        return view('blog')->with([
            'seo_setting' => $seo_setting,
            'blogs' => $blogs,
            'popular_blogs' => $popular_blogs,
            'categories' => $categories,
        ]);
    }

    public function blog_show(Request $request, $slug){
        $blog = Blog::where('status', 1)->where(['slug' => $slug])->first();
        $blog->views += 1;
        $blog->save();

        $blog_comments = BlogComment::orderBy('id','desc')->where('blog_id', $blog->id)->where('status', 1)->get();

        $popular_blogs = Blog::where('is_popular', 'yes')->where('status', 1)->orderBy('id','desc')->get();

        $categories = BlogCategory::where('status', 1)->get();

        return view('blog_detail')->with([
            'blog' => $blog,
            'blog_comments' => $blog_comments,
            'popular_blogs' => $popular_blogs,
            'categories' => $categories,
        ]);
    }

    public function store_comment(Request $request){
        $rules = [
            'blog_id'=>'required',
            'name'=>'required',
            'email'=>'required',
            'comment'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];
        $customMessages = [
            'name.required' => trans('translate.Name is required'),
            'email.required' => trans('translate.Email is required'),
            'comment.required' => trans('translate.Comment is required')
        ];
        $this->validate($request, $rules,$customMessages);

        $blog_comment = new BlogComment();
        $blog_comment->blog_id = $request->blog_id;
        $blog_comment->name = $request->name;
        $blog_comment->email = $request->email;
        $blog_comment->comment = $request->comment;
        $blog_comment->save();

        $notification= trans('translate.Blog comment has submited');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function custom_page($slug){

        $custom_page = CustomPage::where('slug', $slug)->first();

        return view('custom_page')->with([
            'custom_page' => $custom_page,
        ]);
    }

    public function listings(Request $request){

        $seo_setting = SeoSetting::where('id', 10)->first();

        $brands = $this->getMakerOptions();
        $carBrandModels = $this->getCarBrandModelsMap();
        $cities = City::with('translate')->get();
        $engineSizes = Car::query()
            ->whereNotNull('engine_size')
            ->where('engine_size', '!=', '')
            ->distinct()
            ->orderBy('engine_size')
            ->pluck('engine_size');

        $cars = Car::with('dealer', 'brand', 'front_translate')
            ->withCount('galleries')
            ->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved']);

        if($request->location){
            $cars = $cars->where('city_id', $request->location);
        }

        if($request->brands){
            $brand_arr = array();
            foreach($request->brands as $brand_item){
                if($brand_item){
                    $brand_arr[] = $brand_item;
                }
            }

            if(count($brand_arr) > 0){
                $cars = $cars->whereIn('brand_id', $brand_arr);
            }
        }

        if($request->brand_id){
            $brandIds = $this->resolveBrandIdsForSelection($request->brand_id);
            if (count($brandIds) > 0) {
                $cars = $cars->whereIn('brand_id', $brandIds);
            } else {
                $cars = $cars->whereRaw('1 = 0');
            }
        }

        if($request->model){
            $cars = $cars->where('car_model', 'like', '%' . $request->model . '%');
        }

        if($request->seller_type){
            $sellerType = strtolower(trim((string) $request->seller_type));
            if ($sellerType === 'dealer') {
                $cars = $cars->where(function ($query) {
                    $query->where('seller_type', 'like', '%dealer%')
                        ->orWhereHas('dealer', function ($dealerQuery) {
                            $dealerQuery->where('is_dealer', 1);
                        });
                });
            }

            if ($sellerType === 'private') {
                $cars = $cars->where(function ($query) {
                    $query->whereNull('seller_type')
                        ->orWhere('seller_type', 'not like', '%dealer%');
                })->where(function ($query) {
                    $query->whereDoesntHave('dealer')
                        ->orWhereHas('dealer', function ($dealerQuery) {
                            $dealerQuery->where(function ($innerQuery) {
                                $innerQuery->whereNull('is_dealer')
                                    ->orWhere('is_dealer', '!=', 1);
                            });
                        });
                });
            }
        }

        if($request->condition){
            $cars = $cars->whereIn('condition', $request->condition);
        }

        if($request->purpose){

            $purpose_arr = array();
            foreach($request->purpose as $purpose_item){
                if($purpose_item){
                    $purpose_arr[] = $purpose_item;
                }
            }

            if(count($purpose_arr) > 0){
                $cars = $cars->whereIn('purpose', $purpose_arr);
            }
        }

        if($request->features){
            $cars = $cars->whereJsonContains('features', $request->features);
        }

        if($request->price_filter){
            if($request->price_filter == 'low_to_hight'){
                $cars = $cars->orderBy('regular_price', 'asc');
            }

            if($request->price_filter == 'high_to_low'){
                $cars = $cars->orderBy('regular_price', 'desc');
            }

        }

        if($request->search){
            $cars = $cars->whereHas('front_translate', function ($query) use ($request) {
                            $query->where('title', 'like', '%' . $request->search . '%')
                                ->orWhere('description', 'like', '%' . $request->search . '%');
                        });
        }

        if($request->min_year){
            $cars = $cars->whereNotNull('year')->whereRaw('CAST(year AS UNSIGNED) >= ?', [(int)$request->min_year]);
        }

        if($request->max_year){
            $cars = $cars->whereNotNull('year')->whereRaw('CAST(year AS UNSIGNED) <= ?', [(int)$request->max_year]);
        }

        if($request->engine_size){
            $cars = $cars->where('engine_size', $request->engine_size);
        }

        if($request->min_mileage){
            $cars = $cars->whereNotNull('mileage')->whereRaw('CAST(mileage AS UNSIGNED) >= ?', [(int)$request->min_mileage]);
        }

        if($request->max_mileage){
            $cars = $cars->whereNotNull('mileage')->whereRaw('CAST(mileage AS UNSIGNED) <= ?', [(int)$request->max_mileage]);
        }

        if($request->transmission){
            $cars = $cars->where('transmission', $request->transmission);
        }

        if($request->fuel_type){
            $cars = $cars->where(function ($query) use ($request) {
                $query->where('fuel_type', $request->fuel_type)
                    ->orWhere('motorcheck_fuel', $request->fuel_type);
            });
        }

        if($request->min_price){
            $cars = $cars->where('regular_price', '>=', $request->min_price);
        }

        if($request->max_price){
            $cars = $cars->where('regular_price', '<=', $request->max_price);
        }

        if($request->sort_by){
            if($request->sort_by == 'dsc_to_asc'){
                $cars = $cars->whereHas('front_translate', function ($query) use ($request) {
                    $query->orderBy('title', 'desc');
                });
            }

            if($request->sort_by == 'asc_to_dsc'){
                $cars = $cars->whereHas('front_translate', function ($query) use ($request) {
                    $query->orderBy('title', 'asc');
                });
            }

            if($request->sort_by == 'price_low_high'){
                $cars = $cars->orderBy('regular_price', 'asc');
            }

            if($request->sort_by == 'price_high_low'){
                $cars = $cars->orderBy('regular_price', 'desc');
            }

        }

        $cars = $cars->paginate(12);

        $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();

        $features = Feature::with('translate')->get();

        return view('listing', [
            'seo_setting' => $seo_setting,
            'brands' => $brands,
            'carBrandModels' => $carBrandModels,
            'selectedCarModels' => $request->brand_id ? ($carBrandModels[(string) $request->brand_id] ?? []) : [],
            'cities' => $cities,
            'engineSizes' => $engineSizes,
            'features' => $features,
            'cars' => $cars,
            'listing_ads' => $listing_ads,
        ]);
    }


    public function listing($slug){

        $car = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->where('slug', $slug)->firstOrFail();

        $car->total_view +=1;
        $car->save();

        $galleries = CarGallery::where('car_id', $car->id)->get();

        $feature_json_array = array();
        if($car->features != 'null'){
            $feature_json_array = json_decode($car->features);
        }

        $car_features = Feature::whereIn('id', $feature_json_array)->get();

        $related_listings = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->where('brand_id', $car->brand_id)->where('id', '!=', $car->id)->get()->take(6);

        $dealer = User::where(['status' => 'enable' , 'is_banned' => 'no'])->orderBy('id','desc')->select('id','name','username','designation','image','status','is_banned','is_dealer', 'is_vehicle_seller', 'vehicle_company_name', 'address', 'email', 'phone', 'created_at')->where('id', $car->agent_id)->first();

        $reviews = Review::with('user')->where('car_id', $car->id)->where('status', 'enable')->latest()->get();

        $total_dealer_rating = Review::where('agent_id', $car->agent_id)->where('status', 'enable')->count();

        $listing_ads = AdsBanner::where('position_key', 'listing_detail_page_banner')->first();


        return view('listing_detail', [
            'car' => $car,
            'galleries' => $galleries,
            'car_features' => $car_features,
            'related_listings' => $related_listings,
            'dealer' => $dealer,
            'reviews' => $reviews,
            'total_dealer_rating' => $total_dealer_rating,
            'listing_ads' => $listing_ads,
        ]);

    }

    public function dealers(Request $request){

        $seo_setting = SeoSetting::where('id', 11)->first();

        $dealers = User::where(['status' => 'enable' , 'is_banned' => 'no', 'is_dealer' => 1])->where('email_verified_at', '!=', null)->orderBy('id','desc')->select('id','name','username','designation','image','status','is_banned','is_dealer', 'address', 'email', 'phone');

        if($request->search){
            $dealers = $dealers->where('name', 'like', '%' . $request->search . '%');
        }

        if($request->location){
            $dealers = $dealers->whereHas('cars', function($query) use($request){
                $query->where('city_id', $request->location);
            });
        }

        $dealers = $dealers->paginate(12);

        $cities = City::with('translate')->get();

        return view('dealer')->with([
            'seo_setting' => $seo_setting,
            'dealers' => $dealers,
            'cities' => $cities,

        ]);

    }


    public function dealer(Request $request, $username){

        $dealer = User::where(['status' => 'enable' , 'is_banned' => 'no'])->where('email_verified_at', '!=', null)->orderBy('id','desc')->select('id','name','username','designation','image','status','is_banned','is_dealer', 'is_vehicle_seller', 'vehicle_company_name', 'vehicle_company_address', 'is_part_seller', 'part_company_name', 'part_company_address', 'address', 'email', 'phone','facebook','linkedin','twitter','instagram', 'about_me','created_at','sunday','monday','tuesday','wednesday','thursday','friday','saturday','google_map')->where('username', $username)->first();

        if(!$dealer) abort(404);

        $total_dealer_rating = Review::where('agent_id', $dealer->id)->where('status', 'enable')->count();

        $cars = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->where('agent_id', $dealer->id)->paginate(9, ['*'], 'car_page');

        $car_parts = CarPart::with(['agent', 'brand', 'translations'])->withCount('galleries')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->where('agent_id', $dealer->id)->paginate(9, ['*'], 'part_page');

        $dealer_ads = AdsBanner::where('position_key', 'dealer_detail_page_banner')->first();

        return view('dealer_detail', [
            'dealer' => $dealer,
            'cars' => $cars,
            'car_parts' => $car_parts,
            'total_dealer_rating' => $total_dealer_rating,
            'dealer_ads' => $dealer_ads,
        ]);
    }

    public function send_message_to_dealer(ContactMessageRequest $request, $dealer_id){
        MailHelper::setMailConfig();
    try {
        $template = EmailTemplate::find(2);
        $message = $template->description;
        $subject = $template->subject;
        $message = str_replace('{{user_name}}',$request->name,$message);
        $message = str_replace('{{user_email}}',$request->email,$message);
        $message = str_replace('{{user_phone}}',$request->phone,$message);
        $message = str_replace('{{message_subject}}',$request->subject,$message);
        $message = str_replace('{{message}}',$request->message,$message);

        $dealer = User::findOrFail($dealer_id);

        Mail::to($dealer->email)->send(new SendContactMessage($message,$subject, $request->email, $request->name));
    } catch (\Exception $e) {
        \Log::error('Mail send error: ' . $e->getMessage());
    }
        $notification= trans('translate.Your message has send successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function pricing_plan(){

        $user = Auth::guard('web')->user();
        if($user && !$user->is_dealer){
            $notification = trans('translate.You are not allowed to access this page');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('user.dashboard')->with($notification);
        }

        $subscription_plans = SubscriptionPlan::orderBy('serial', 'asc')->where('status', 'active')->get();
        $setting = Setting::first();

        return view('pricing_plan', ['subscription_plans' => $subscription_plans, 'setting' => $setting]);
    }

     public function join_as_dealer(){

        return redirect()->route('register');
    }

   public function compare(){

        $compare_array = Session::get('compare_array', []);

        $cars = Car::with('brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->whereIn('id', $compare_array)->get();

        $compare_qty = $cars->count();


        return view('compare', ['cars' => $cars, 'compare_qty' => $compare_qty]);
   }

   public function add_to_compare($id){

        $compare_array = Session::get('compare_array', []);

        if (!in_array($id, $compare_array)) {
            if(count($compare_array) < 4){
                $compare_array[] = $id;
                Session::put('compare_array', $compare_array);

                $notification= trans('translate.Item added successfully');
                $notification=array('messege'=>$notification,'alert-type'=>'success');
                return redirect()->back()->with($notification);
            }else{
                $notification= trans('translate.You can not added more then 4 items');
                $notification=array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->back()->with($notification);
            }

        }else{
            $notification= trans('translate.Item already exist in compare');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
   }

    public function remove_to_compare($car_id){

        $compare_array = Session::get('compare_array', []);

        $update_compare_array = array_filter($compare_array, function ($id) use ($car_id) {
            return $id !== $car_id;
        });

        Session::put('compare_array', $update_compare_array);

        $notification= trans('translate.Compare item removed successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function language_switcher(Request $request){

        $request_lang = Language::where('lang_code', $request->lang_code)->first();

        Session::put('front_lang', $request->lang_code);
        Session::put('front_lang_name', $request_lang->lang_name);
        Session::put('lang_dir', $request_lang->lang_direction);

        app()->setLocale($request->lang_code);

        $notification= trans('translate.Language switched successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function currency_switcher(Request $request){

        $request_currency = MultiCurrency::where('currency_code', $request->currency_code)->first();

        Session::put('currency_name', $request_currency->currency_name);
        Session::put('currency_code', $request_currency->currency_code);
        Session::put('currency_icon', $request_currency->currency_icon);
        Session::put('currency_rate', $request_currency->currency_rate);
        Session::put('currency_position', $request_currency->currency_position);

        $notification= trans('translate.Currency switched successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);




    }

    public function cities_by_country($country_id){

        $cities = City::where('country_id', $country_id)->get();

        $html_response = "<option value=''>".trans('translate.Select City')."</option>";

        foreach($cities as $ciy){
            $new_item = "<option value='$ciy->id'>".$ciy->name."</option>";

            $html_response .= $new_item;
        }

        return response()->json($html_response);

    }

    public function car_parts(Request $request)
    {
        $brands = $this->getMakerOptions();
        $partBrandModels = $this->getPartBrandModelsMap();

        $car_parts = CarPart::with('brand', 'translations', 'agent')
            ->withCount('galleries')
            ->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->orderBy('id', 'desc');

        if($request->brand_id){
            $brandIds = $this->resolveBrandIdsForSelection($request->brand_id);
            if (count($brandIds) > 0) {
                $car_parts = $car_parts->whereIn('brand_id', $brandIds);
            } else {
                $car_parts = $car_parts->whereRaw('1 = 0');
            }
        }

        if($request->model){
            $car_parts = $car_parts->where('compatibility', 'like', '%' . $request->model . '%');
        }

        if($request->min_price){
            $car_parts = $car_parts->where('regular_price', '>=', $request->min_price);
        }

        if($request->max_price){
            $car_parts = $car_parts->where('regular_price', '<=', $request->max_price);
        }

        if($request->search){
            $car_parts = $car_parts->whereHas('frontTranslate', function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $car_parts = $car_parts->paginate(12);
        $car_parts = $car_parts->appends($request->all());

        return view('car_parts', [
            'car_parts' => $car_parts,
            'brands' => $brands,
            'partBrandModels' => $partBrandModels,
            'selectedPartModels' => $request->brand_id ? ($partBrandModels[(string) $request->brand_id] ?? []) : [],
        ]);
    }

    public function car_part($slug)
    {
        $car_part = CarPart::with('brand', 'translations', 'galleries', 'agent')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->where('slug', $slug)->firstOrFail();

        return view('car_part_detail', [
            'car_part' => $car_part,
        ]);
    }


    public function placeholderImage($size = null)
    {
        if (!$size) {
            $size = '336x210';
        }

        if (!preg_match('/^\d+x\d+$/', $size)) {
            return redirect('https://placehold.co/800x600?text=Invalid+Size');
        }

        $dimensions = explode('x', $size);
        $imgWidth = (int)$dimensions[0];
        $imgHeight = (int)$dimensions[1];

        $maxWidth = 2000;
        $maxHeight = 2000;
        $imgWidth = min($imgWidth, $maxWidth);
        $imgHeight = min($imgHeight, $maxHeight);

        $imageUrl = "https://placehold.co/{$imgWidth}x{$imgHeight}?text={$imgWidth}x{$imgHeight}";

        return redirect($imageUrl);
    }

}
