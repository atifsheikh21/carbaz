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

    public function landing(Request $request)
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }

        $brands = Brand::where('status', 'enable')->get();

        return view('landing_search', [
            'brands' => $brands,
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
            return CarPart::with('brand', 'frontTranslate')->where(function ($query) {
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

        $brands = Brand::where('status', 'enable')->get();

        $cars = Car::with('dealer', 'brand', 'front_translate')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved']);

        if($request->country){
            $cars = $cars->where('country_id', $request->country);
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

        if($request->brand_id){
            $cars = $cars->where('brand_id', $request->brand_id);
        }

        if($request->model){
            $cars = $cars->where('car_model', 'like', '%' . $request->model . '%');
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

        $cities = [];
        if($request->country){

            $cities = City::with('translate')->where('country_id', $request->country)->get();
        }

        $features = Feature::with('translate')->get();

        $countries = Country::latest()->get();

        return view('listing', [
            'seo_setting' => $seo_setting,
            'brands' => $brands,
            'cities' => $cities,
            'features' => $features,
            'cars' => $cars,
            'listing_ads' => $listing_ads,
            'countries' => $countries,
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

        $dealer = User::where(['status' => 'enable' , 'is_banned' => 'no', 'is_dealer' => 1])->where('email_verified_at', '!=', null)->orderBy('id','desc')->select('id','name','username','designation','image','status','is_banned','is_dealer', 'address', 'email', 'phone', 'created_at')->where('id', $car->agent_id)->first();

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

        $dealer = User::where(['status' => 'enable' , 'is_banned' => 'no', 'is_dealer' => 1])->where('email_verified_at', '!=', null)->orderBy('id','desc')->select('id','name','username','designation','image','status','is_banned','is_dealer', 'address', 'email', 'phone','facebook','linkedin','twitter','instagram', 'about_me','created_at','sunday','monday','tuesday','wednesday','thursday','friday','saturday','google_map')->where('username', $username)->first();

        if(!$dealer) abort(404);

        $total_dealer_rating = Review::where('agent_id', $dealer->id)->where('status', 'enable')->count();

        $cars = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->where('agent_id', $dealer->id)->paginate(9);

        $dealer_ads = AdsBanner::where('position_key', 'dealer_detail_page_banner')->first();

        return view('dealer_detail', [
            'dealer' => $dealer,
            'cars' => $cars,
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

        $subscription_plans = SubscriptionPlan::orderBy('serial', 'asc')->where('status', 'active')->get();

        return view('pricing_plan', ['subscription_plans' => $subscription_plans]);
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
        $brands = Brand::where('status', 'enable')->get();

        $car_parts = CarPart::with('brand', 'frontTranslate')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->orderBy('id', 'desc');

        if($request->brand_id){
            $car_parts = $car_parts->where('brand_id', $request->brand_id);
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
        ]);
    }

    public function car_part($slug)
    {
        $car_part = CarPart::with('brand', 'frontTranslate')->where(function ($query) {
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
