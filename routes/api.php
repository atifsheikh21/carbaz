<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;






Route::group(['middleware' => ['HtmlSpecialchars', 'CurrencyLangaugeForAPI'], 'as' => 'api.'], function () {

    Route::controller(HomeController::class)->group(function () {

        Route::get('/website-setup', 'website_setup')->name('website-setup');
        Route::get('/', 'index')->name('home');
        Route::get('/all-brands', 'all_brands')->name('all-brands');

        Route::get('/listings-filter-option', 'listings_filter_option')->name('listings-filter-option');
        Route::get('/listings', 'listings')->name('listings');
        Route::get('/listing/{id}', 'listing')->name('listing');

        Route::get('/terms-conditions', 'terms_conditions')->name('terms-conditions');
        Route::get('/privacy-policy', 'privacy_policy')->name('privacy-policy');


        Route::get('/dealers', 'dealers')->name('dealers');
        Route::get('/dealers-filter-option', 'dealers_filter_option')->name('dealers-filter-option');
        Route::get('/dealer/{slug}', 'dealer')->name('dealer');
        Route::post('/send-message-to-dealer/{id}', 'send_message_to_dealer')->name('send-message-to-dealer');




        Route::get('/join-as-dealer', 'join_as_dealer')->name('join-as-dealer');

        Route::get('/language-switcher', 'language_switcher')->name('language-switcher');
        Route::get('/currency-switcher', 'currency_switcher')->name('currency-switcher');

        Route::get('/cities-by-country/{id}', 'cities_by_country')->name('cities-by-country');

    });





     // Login route
     Route::post('/store-login', [LoginController::class, 'store_login'])->name('store-login');

     Route::post('/store-register', [RegisterController::class, 'store_register'])->name('store-register');
     Route::post('/seller/store-register', [RegisterController::class, 'seller_store_register'])->name('seller-store-register');
     Route::post('/resend-register', [RegisterController::class, 'resend_register_code'])->name('resend-register');
     Route::post('/user-verification', [RegisterController::class, 'register_verification'])->name('user-verification');

     Route::post('/send-forget-password', [LoginController::class, 'send_custom_forget_pass'])->name('send-forget-password');
     Route::post('/verify-forget-password-otp', [LoginController::class, 'verify_custom_reset_password'])->name('verify-forget-password-otp');
     Route::post('/store-reset-password', [LoginController::class, 'store_reset_password'])->name('store-reset-password');

     Route::get('/user-logout', [LoginController::class, 'userLogout'])->name('user.logout');

     // Login route end







     Route::group(['as'=> 'user.', 'prefix' => 'user', 'middleware' => ['auth:api']],function (){


        Route::controller(PaymentController::class)->group(function () {

            Route::post('/payment', 'payment')->name('payment');

            Route::post('/pay-via-stripe', 'pay_via_stripe')->name('pay-via-stripe');
            Route::post('/pay-via-bank', 'pay_via_bank')->name('pay-via-bank');
            Route::post('/pay-via-razorpay', 'pay_via_razorpay')->name('pay-via-razorpay');
            Route::post('/pay-via-flutterwave', 'pay_via_flutterwave')->name('pay-via-flutterwave');
            Route::get('/pay-via-paystack', 'pay_via_payStack')->name('pay-via-paystack');
            Route::get('/pay-via-mollie', 'pay_via_mollie')->name('pay-via-mollie');
            Route::get('/mollie-payment-success', 'mollie_payment_success')->name('mollie-payment-success');
            Route::get('/pay-via-instamojo', 'pay_via_instamojo')->name('pay-via-instamojo');
            Route::get('/response-instamojo', 'instamojo_response')->name('response-instamojo');

        });


        Route::controller(ProfileController::class)->group(function () {

            Route::get('/dashboard', 'dashboard')->name('dashboard');

            Route::get('/edit-profile', 'edit')->name('edit-profile');
            Route::put('/update-profile', 'update')->name('update-profile');

            Route::get('/change-password', 'change_password')->name('change-password');
            Route::post('/update-password', 'update_password')->name('update-password');
            Route::post('/upload-user-avatar', 'upload_user_avatar')->name('upload-user-avatar');

            Route::get('/transactions', 'transactions')->name('transactions');

            Route::get('/wishlists', 'wishlists')->name('wishlists');
            Route::get('/add-to-wishlist/{id}', 'add_to_wishlist')->name('add-to-wishlist');
            Route::delete('/remove-wishlist/{id}', 'remove_wishlist')->name('remove-wishlist');

            Route::get('/reviews', 'reviews')->name('reviews');
            Route::post('/store-review', 'store_review')->name('store-review');
        });

    });


});
