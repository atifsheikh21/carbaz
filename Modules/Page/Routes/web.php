<?php

use Modules\Page\Http\Controllers\TermsConditionController;
use Modules\Page\Http\Controllers\PrivacyPolicyController;
use Modules\Page\Http\Controllers\FaqController;
use Modules\Page\Http\Controllers\CustomPageController;
use Modules\Page\Http\Controllers\ContactUsController;
use Modules\Page\Http\Controllers\AboutUsController;
use Modules\Page\Http\Controllers\IntroController;
use Modules\Page\Http\Controllers\VideoController;
use Modules\Page\Http\Controllers\JoinDealerController;
use Modules\Page\Http\Controllers\MobileAppController;
use Modules\Page\Http\Controllers\WorkingStepController;
use Modules\Page\Http\Controllers\CounterController;
use Modules\Page\Http\Controllers\CallUsController;
use Modules\Page\Http\Controllers\LoanController;

Route::group(['as'=> 'admin.', 'prefix' => 'admin', 'middleware' => ['XSS','DEMO','auth:admin']],function (){

    Route::controller(TermsConditionController::class)->group(function () {
        Route::get('terms-and-conditions', 'index')->name('terms-and-conditions');
        Route::put('update-terms-and-conditions', 'update')->name('update-terms-and-conditions');
    });

    Route::controller(PrivacyPolicyController::class)->group(function () {
        Route::get('privacy-policy', 'index')->name('privacy-policy');
        Route::put('update-privacy-policy', 'update')->name('update-privacy-policy');
    });

    Route::resources([
        'faq' => FaqController::class,
        'custom-page' => CustomPageController::class,
    ]);

    Route::controller(ContactUsController::class)->group(function () {
        Route::get('contact-us', 'index')->name('contact-us');
        Route::put('update-contact-us', 'update')->name('update-contact-us');
    });

    Route::controller(AboutUsController::class)->group(function () {
        Route::get('about-us', 'index')->name('about-us');
        Route::put('update-about-us', 'update')->name('update-about-us');
    });

    Route::controller(IntroController::class)->group(function () {
        Route::get('home1-intro', 'index')->name('home1-intro');
        Route::put('update-home1-intro', 'update')->name('update-home1-intro');

        Route::get('home2-intro', 'home2_intro')->name('home2-intro');
        Route::put('update-home2-intro', 'home2_intro_update')->name('update-home2-intro');

        Route::get('home3-intro', 'home3_intro')->name('home3-intro');
        Route::put('update-home3-intro', 'home3_intro_update')->name('update-home3-intro');
    });

    Route::controller(VideoController::class)->group(function () {
        Route::get('video-section', 'index')->name('video-section');
        Route::put('update-video-section', 'update')->name('update-video-section');
    });

    Route::controller(JoinDealerController::class)->group(function () {
        Route::get('join-as-dealer', 'index')->name('join-as-dealer');
        Route::put('update-join-as-dealer', 'update')->name('update-join-as-dealer');
    });

    Route::controller(MobileAppController::class)->group(function () {
        Route::get('mobile-app', 'index')->name('mobile-app');
        Route::put('update-mobile-app', 'update')->name('update-mobile-app');
    });

    Route::controller(WorkingStepController::class)->group(function () {
        Route::get('working-step', 'index')->name('working-step');
        Route::put('update-working-step', 'update')->name('update-working-step');
    });

    Route::controller(CounterController::class)->group(function () {
        Route::get('counter', 'index')->name('counter');
        Route::put('update-counter', 'update')->name('update-counter');
    });

    Route::controller(CallUsController::class)->group(function () {
        Route::get('call-us', 'index')->name('call-us');
        Route::put('update-call-us', 'update')->name('update-call-us');
    });

    Route::controller(LoanController::class)->group(function () {
        Route::get('loan-calculator', 'index')->name('loan-calculator');
        Route::put('update-loan-calculator', 'update')->name('update-loan-calculator');
    });

});

