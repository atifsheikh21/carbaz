<?php

namespace App\Http\Controllers\API;
use Exception;


use Carbon\Carbon;

use App\Models\User;
use App\Models\Offer;
use App\Models\Review;
use App\Models\Booking;
use App\Models\CarType;
use App\Models\AdsBanner;
use App\Helpers\MailHelper;
use Illuminate\Http\Request;
use Modules\Car\Entities\Car;
use Modules\City\Entities\City;
use Modules\Brand\Entities\Brand;
use Modules\Page\Entities\HomePage;
use Modules\Slider\Entities\Slider;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Car\Entities\CarGallery;
use Modules\Country\Entities\Country;
use Modules\Feature\Entities\Feature;
use Modules\Language\Entities\Language;
use Modules\Page\Entities\PrivacyPolicy;
use Modules\Page\Entities\TermAndCondition;

use Modules\GeneralSetting\Entities\Setting;
use Modules\Currency\app\Models\MultiCurrency;
use Modules\BannerSlider\Entities\BannerSlider;
use Modules\GeneralSetting\Entities\SeoSetting;
use Modules\GeneralSetting\Entities\EmailTemplate;
use Str, Mail, Hash, Auth, Session,Config,Artisan;
use Modules\Subscription\Entities\SubscriptionPlan;
use Modules\ContactMessage\Emails\SendContactMessage;
use Modules\ContactMessage\Http\Requests\ContactMessageRequest;

class HomeController extends Controller
{


    public function website_setup(Request $request){


        $setting = Setting::select('id', 'logo', 'app_name', 'timezone', 'default_avatar')->first();


        $language_list = Language::where('status', 1)->get();
        $currency_list = MultiCurrency::where('status', 'active')->get();


        $lang_code = 'en';

        if($request->lang_code){
            $lang_code = $request->lang_code;
        }else{
            $default_lang = Language::where('id', 1)->first();
            if($default_lang){
                $lang_code = $default_lang->lang_code;
            }else{
                $lang_code = 'en';
            }
        }

        try{

            $localizations = include(lang_path($lang_code.'/translate.php'));
        }catch(Exception $ex){
            return response()->json([
                'message' => trans('Localication unprocessable')
            ],403);
        }


        return response()->json([
            'setting' => $setting,
            'language_list' => $language_list,
            'currency_list' => $currency_list,
            'localizations' => $localizations,
        ]);


    }

    public function index(Request $request){

        $sliders = Slider::where(['status' => 'active'])->get();

        $brands = Brand::where('status', 'enable')->get();

        $featured_cars = Car::with('brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['is_featured' => 'enable', 'status' => 'enable', 'approved_by_admin' => 'approved'])->select('id', 'slug', 'brand_id', 'expired_date', 'regular_price', 'offer_price', 'thumb_image', 'purpose', 'condition', 'is_featured', 'status', 'approved_by_admin')->take(6)->get();


        $ads_banners = BannerSlider::where('status','enable')->get();

        $dealers = User::where(['status' => 'enable' , 'is_banned' => 'no', 'is_dealer' => 1])->where('email_verified_at', '!=', null)->orderBy('id','desc')->select('id','name','username','designation','image','status','is_banned','is_dealer', 'address', 'email', 'phone', 'kyc_status')->take(6)->get();

        $homepage = HomePage::with('front_translate')->first();

        $join_dealer = (object) array(
            'image' => $homepage?->dealer_bg_image,
            'short_title' => $homepage?->dealer_short_title,
            'title' => $homepage?->dealer_title,
        );

        $latest_cars = Car::with('brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->select('id', 'slug', 'brand_id', 'expired_date', 'regular_price', 'offer_price', 'thumb_image', 'purpose', 'condition', 'is_featured', 'status', 'approved_by_admin')->take(10)->get();

        return response()->json([
            'sliders' => $sliders,
            'brands' => $brands,
            'featured_cars' => $featured_cars,
            'ads_banners' => $ads_banners,
            'dealers' => $dealers,
            'join_dealer' => $join_dealer,
            'latest_cars' => $latest_cars,
        ]);

    }


    public function all_brands(){
        $brands = Brand::where('status', 'enable')->get();

        return response()->json([
            'brands' => $brands,
        ]);
    }

    public function terms_conditions(){

        $terms_condition = TermAndCondition::where('lang_code', Session::get('front_lang'))->first();

        return response()->json([
            'terms_condition' => $terms_condition,
        ]);
    }

    public function privacy_policy(){

        $privacy_policy = PrivacyPolicy::where('lang_code', Session::get('front_lang'))->first();

        return response()->json([
            'privacy_policy' => $privacy_policy,
        ]);
    }


    public function listings_filter_option(Request $request){

        $brands = Brand::where('status', 'enable')->get();

        $cities = [];
        if($request->country){

            $cities = City::with('translate')->where('country_id', $request->country)->get();
        }

        $features = Feature::all();

        $countries = Country::latest()->get();

        $conditions = ['New', 'Used'];

        $purposes = ['Sale'];

        $max_price_course = Car::orderBy('regular_price', 'desc')->first();
        $rang_slider_max_price = $max_price_course?->regular_price ?? 0;

        return response()->json([
            'brands' => $brands,
            'cities' => $cities,
            'features' => $features,
            'countries' => $countries,
            'conditions' => $conditions,
            'purposes' => $purposes,
            'rang_slider_max_price' => $rang_slider_max_price,
        ]);
    }


    public function listings(Request $request){

        $cars = Car::with('brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->select('id', 'slug', 'brand_id', 'expired_date', 'regular_price', 'offer_price', 'thumb_image', 'purpose', 'condition', 'is_featured', 'status', 'approved_by_admin')->orderBy('id', 'desc');


        if($request->country_id){
            $cars = $cars->where('country_id', $request->country_id);
        }

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


        if($request->search){
            $cars = $cars->whereHas('front_translate', function ($query) use ($request) {
                            $query->where('title', 'like', '%' . $request->search . '%')
                                ->orWhere('description', 'like', '%' . $request->search . '%');
                        });
        }


        $cars = $cars->paginate(12);

        $cars = $cars->appends($request->all());

        return response()->json([
            'cars' => $cars,
        ]);
    }


    public function listing($id){

        $car = Car::with('brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->where('id', $id)->first();

        if(!$car){
            $message = trans('translate.Listing Not Found!');
            return response()->json([
                'message' => $message
            ], 403);
        }

        $car->total_view +=1;
        $car->save();

        $galleries = CarGallery::where('car_id', $car->id)->get();

        $related_listings = Car::with('brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->where('brand_id', $car->brand_id)->where('id', '!=', $car->id)->select('id', 'slug', 'brand_id', 'expired_date', 'regular_price', 'offer_price', 'thumb_image', 'purpose', 'condition', 'is_featured', 'status', 'approved_by_admin')->get()->take(6);

        $dealer = User::where(['status' => 'enable' , 'is_banned' => 'no', 'is_dealer' => 1])->where('email_verified_at', '!=', null)->orderBy('id','desc')->select('id','name','username','designation','image','status','is_banned','is_dealer', 'address', 'email', 'phone', 'created_at', 'kyc_status')->where('id', $car->agent_id)->first();

        $reviews = Review::with('user')->where('car_id', $car->id)->where('status', 'enable')->latest()->get();

        $total_dealer_rating = Review::where('agent_id', $car->agent_id)->where('status', 'enable')->count();


        $feature_json_array = array();
        if($car->features != 'null'){
            $feature_json_array = json_decode($car->features);
        }

        $car_features = Feature::whereIn('id', $feature_json_array)->get();

        return response()->json([
            'car' => $car,
            'car_features' => $car_features,
            'galleries' => $galleries,
            'related_listings' => $related_listings,
            'dealer' => $dealer,
            'reviews' => $reviews,
            'total_dealer_rating' => $total_dealer_rating,
            'average_rating' => $car->averageRating ?? 0.0,
        ]);

    }

    public function dealers(Request $request){

        $dealers = User::where(['status' => 'enable' , 'is_banned' => 'no', 'is_dealer' => 1])->where('email_verified_at', '!=', null)->orderBy('id','desc')->select('id','name','username','designation','image','status','is_banned','is_dealer', 'address', 'email', 'phone', 'kyc_status');

        if($request->search){
            $dealers = $dealers->where('name', 'like', '%' . $request->search . '%');
        }

        if($request->location){
            $dealers = $dealers->whereHas('cars', function($query) use($request){
                $query->where('city_id', $request->location);
            });
        }

        $dealers = $dealers->paginate(12);


        return response()->json([
            'dealers' => $dealers,

        ]);

    }


    public function dealers_filter_option(Request $request){
        $cities = City::all();

        return response()->json([
            'cities' => $cities,

        ]);

    }


    public function dealer(Request $request, $username){

        $dealer = User::where(['status' => 'enable' , 'is_banned' => 'no', 'is_dealer' => 1])->where('email_verified_at', '!=', null)->orderBy('id','desc')->select('id','name','username','designation','image','status','is_banned','is_dealer', 'address', 'email', 'phone','facebook','linkedin','twitter','instagram', 'about_me','created_at','sunday','monday','tuesday','wednesday','thursday','friday','saturday','google_map', 'kyc_status', 'banner_image')->where('username', $username)->first();

        if(!$dealer){
            return response()->json([
                'message' => trans('translate.Dealer Not Found!')
            ], 403);
        }

        $total_dealer_rating = Review::where('agent_id', $dealer->id)->where('status', 'enable')->count();

        $cars = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->where('agent_id', $dealer->id)->select('id', 'slug', 'brand_id', 'expired_date', 'regular_price', 'offer_price', 'thumb_image', 'purpose', 'condition', 'is_featured', 'status', 'approved_by_admin')->paginate(9);

        $dealer_ads = AdsBanner::where('position_key', 'dealer_detail_page_banner')->first();

        return response()->json([
            'dealer' => $dealer,
            'cars' => $cars,
            'total_dealer_rating' => $total_dealer_rating,
        ]);
    }

    public function send_message_to_dealer(ContactMessageRequest $request, $dealer_id){
        MailHelper::setMailConfig();

        $template = EmailTemplate::find(2);
        $message = $template->description;
        $subject = $template->subject;
        $message = str_replace('{{user_name}}',$request->name,$message);
        $message = str_replace('{{user_email}}',$request->email,$message);
        $message = str_replace('{{user_phone}}',$request->phone,$message);
        $message = str_replace('{{message_subject}}',$request->subject,$message);
        $message = str_replace('{{message}}',$request->message,$message);

        $dealer = User::findOrFail($dealer_id);

        try{
            Mail::to($dealer->email)->send(new SendContactMessage($message,$subject, $request->email, $request->name));
        }catch(Exception $ex){
            Log::error($ex->getMessage());
        }

        $notification= trans('translate.Your message has send successfully');
        return response()->json([
            'message' => $notification
        ]);
    }

    public function pricing_plan(){

        $subscription_plans = SubscriptionPlan::orderBy('serial', 'asc')->where('status', 'active')->get();

        return view('pricing_plan', ['subscription_plans' => $subscription_plans]);
    }

     public function join_as_dealer(){

        return redirect()->route('user.kyc');
    }


    public function language_switcher(Request $request){

        $request_lang = Language::where('lang_code', $request->lang_code)->first();

        Session::put('front_lang', $request->lang_code);
        Session::put('front_lang_name', $request_lang->lang_name);
        Session::put('lang_dir', $request_lang->lang_direction);

        app()->setLocale($request->lang_code);

        $notification= trans('All Language keywords are not implemented in demo mode. Admin can translate every word from the admin panel');
        $notification=array('messege'=>$notification,'alert-type'=>'warning');
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

        return response()->json([
            'cities' => $cities,
        ]);

    }







}
