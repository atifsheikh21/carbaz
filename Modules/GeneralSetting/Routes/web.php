<?php

use Modules\GeneralSetting\Http\Controllers\GeneralSettingController;
use Modules\GeneralSetting\Http\Controllers\EmailConfigController;
use Modules\GeneralSetting\Http\Controllers\ContactMessageController;
use Modules\GeneralSetting\Http\Controllers\PaymentMethodController;


Route::group(['as'=> 'admin.', 'prefix' => 'admin', 'middleware' => ['XSS','DEMO','auth:admin']],function (){

    Route::controller(GeneralSettingController::class)->group(function () {

        Route::group(['prefix' => 'configuration'],function (){

            Route::get('general-setting', 'general_setting')->name('general-setting');
            Route::put('update-general-setting', 'update_general_setting')->name('update-general-setting');
            Route::put('update-logo-favicon', 'update_logo_favicon')->name('update-logo-favicon');

            Route::put('update-tawk-chat', 'update_tawk_chat')->name('update-tawk-chat');
            Route::put('update-google-captcha', 'update_google_captcha')->name('update-google-captcha');
            Route::put('update-facebook-pixel', 'update_facebook_pixel')->name('update-facebook-pixel');
            Route::put('update-google-analytic', 'update_google_analytic')->name('update-google-analytic');

            Route::get('cookie-consent', 'cookie_consent')->name('cookie-consent');
            Route::put('update-cookie-consent', 'update_cookie_consent')->name('update-cookie-consent');

            Route::get('login-image', 'login_image')->name('login-image');
            Route::put('update-login-image', 'update_login_image')->name('update-login-image');

            Route::get('admin-login-image', 'admin_login_image')->name('admin-login-image');
            Route::put('update-admin-login-image', 'admin_update_login_image')->name('update-admin-login-image');



            Route::get('social-login', 'social_login')->name('social-login');
            Route::put('update-social-login', 'update_social_login')->name('update-social-login');

            Route::get('error-image', 'error_image')->name('error-image');
            Route::put('update-error-image', 'update_error_image')->name('update-error-image');

            Route::get('default-avatar', 'default_avatar')->name('default-avatar');
            Route::put('update-default-avatar', 'update_default_avatar')->name('update-default-avatar');

            Route::get('default-placeholder', 'default_placeholder')->name('default-placeholder');
            Route::put('update-default-placeholder', 'update_default_placeholder')->name('update-default-placeholder');

            Route::get('header-footer', 'header_footer')->name('header-footer');
            Route::put('update-header-footer/{id}', 'update_header_footer')->name('update-header-footer');

            Route::get('seo-setup', 'seo_setting')->name('seo-setup');
            Route::put('update-seo-setting/{id}', 'update_seo_setting')->name('update-seo-setting');

            Route::get('breadcrumb', 'breadcrumb')->name('breadcrumb');
            Route::put('update-breadcrumb', 'update_breadcrumb')->name('update-breadcrumb');

            Route::get('cache-clear', 'cache_clear')->name('cache-clear');
            Route::delete('db-clear', 'database_destroy')->name('db-clear');

            Route::get('maintenance-mode', 'maintenance_mode')->name('maintenance-mode');
            Route::put('update-maintenance-mode', 'update_maintenance_mode')->name('update-maintenance-mode');

            Route::put('update-add-listing', 'update_add_listing')->name('update-add-listing');

        });

    });

    Route::controller(EmailConfigController::class)->group(function () {

        Route::group(['prefix' => 'configuration'],function (){
            Route::get('email-configuration', 'email_configuration')->name('email-configuration');
            Route::put('update-email-configuration', 'update_email_configuration')->name('update-email-configuration');

            Route::get('email-template', 'email_template')->name('email-template');
            Route::get('edit-email-template/{id}', 'edit_email_template')->name('edit-email-template');
            Route::put('update-email-template/{id}', 'update_email_template')->name('update-email-template');
        });

    });

    Route::controller(PaymentMethodController::class)->group(function () {

        Route::group(['prefix' => 'configuration'],function (){
            Route::get('payment-method', 'index')->name('payment-method');
            Route::put('update-paypal', 'updatePaypal')->name('update-paypal');
            Route::put('update-stripe', 'updateStripe')->name('update-stripe');
            Route::put('update-razorpay', 'updateRazorpay')->name('update-razorpay');
            Route::put('update-bank', 'updateBank')->name('update-bank');
            Route::put('update-mollie', 'updateMollie')->name('update-mollie');
            Route::put('update-paystack', 'updatePayStack')->name('update-paystack');
            Route::put('update-flutterwave', 'updateflutterwave')->name('update-flutterwave');
            Route::put('update-instamojo', 'updateInstamojo')->name('update-instamojo');
        });


    });

});



