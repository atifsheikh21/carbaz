<?php

use Illuminate\Http\Request;
use Modules\Subscription\Http\Controllers\API\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::group(['middleware' => ['XSS','DEMO', 'CurrencyLangaugeForAPI']], function () {

    Route::group(['as'=> 'user.', 'prefix' => 'user', 'middleware' => ['auth:api']],function (){

        Route::get('/pricing-plan', [PaymentController::class, 'pricing_plan'])->name('pricing-plan');


        Route::get('/free-enroll/{id}', [PaymentController::class, 'free_enroll'])->name('free-enroll');
        Route::get('/payment-info', [PaymentController::class, 'payment'])->name('payment-info');
        Route::post('/pay-with-bank/{id}', [PaymentController::class, 'pay_via_bank'])->name('pay-with-bank');
        Route::post('/pay-with-stripe/{id}', [PaymentController::class, 'pay_via_stripe'])->name('pay-with-stripe');



    });
});

