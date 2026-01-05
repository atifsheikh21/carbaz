<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Modules\GeneralSetting\Entities\Setting;
use Modules\Language\Entities\Language;
use Modules\Page\Entities\HomePage;
use Modules\Page\Entities\CustomPage;
use Modules\GeneralSetting\Entities\GoogleRecaptcha;
use Modules\GeneralSetting\Entities\GoogleAnalytic;
use Modules\GeneralSetting\Entities\FacebookPixel;
use Modules\GeneralSetting\Entities\TawkChat;
use Modules\GeneralSetting\Entities\CookieConsent;
use Modules\Currency\app\Models\MultiCurrency;
use Modules\Blog\Entities\Blog;
use View;
use Session;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Session::put('admin_lang', 'en');

        View::composer('*', function($view){
            $setting = Cache::remember('global.setting', 60, function () {
                return Setting::first();
            });

            $language_list = Cache::remember('global.language_list', 300, function () {
                return Language::where('status', 1)->get();
            });

            $currency_list = Cache::remember('global.currency_list', 300, function () {
                return MultiCurrency::where('status', 'active')->get();
            });

            $google_recaptcha = Cache::remember('global.google_recaptcha', 300, function () {
                return GoogleRecaptcha::first();
            });

            $custom_pages = Cache::remember('global.custom_pages', 300, function () {
                return CustomPage::where('status', 1)->get();
            });

            $google_analytic = Cache::remember('global.google_analytic', 300, function () {
                return GoogleAnalytic::first();
            });

            $facebook_pixel = Cache::remember('global.facebook_pixel', 300, function () {
                return FacebookPixel::first();
            });

            $tawk_chat = Cache::remember('global.tawk_chat', 300, function () {
                return TawkChat::first();
            });

            $cookie_consent = Cache::remember('global.cookie_consent', 300, function () {
                return CookieConsent::first();
            });

            $footer_blogs = Cache::remember('global.footer_blogs', 300, function () {
                return Blog::where('status', 1)->orderBy('id','desc')->take(2)->get();
            });

            $view->with('breadcrumb', $setting ? $setting->breadcrumb_image : null);
            $view->with('setting', $setting);
            $view->with('language_list', $language_list);
            $view->with('currency_list', $currency_list);
            $view->with('google_recaptcha', $google_recaptcha);
            $view->with('custom_pages', $custom_pages);
            $view->with('google_analytic', $google_analytic);
            $view->with('facebook_pixel', $facebook_pixel);
            $view->with('tawk_chat', $tawk_chat);
            $view->with('cookie_consent', $cookie_consent);
            $view->with('footer_blogs', $footer_blogs);
        });
    }
}
