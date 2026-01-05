<?php


use Modules\Subscription\Http\Controllers\API\PaypalController;
use Modules\Subscription\Http\Controllers\API\PaymentController;
use Modules\Subscription\Http\Controllers\SubscriptionLogController;
use Modules\Subscription\Http\Controllers\SubscriptionPlanController;


Route::group(['as'=> 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'XSS','DEMO']],function (){
    Route::resource('subscription-plan', SubscriptionPlanController::class);
    Route::get('purchase-history', [SubscriptionLogController::class, 'index'])->name('purchase-history');
    Route::get('pending-purchase-history', [SubscriptionLogController::class, 'pending_index'])->name('pending-purchase-history');

    Route::get('purchase-history-detail/{id}', [SubscriptionLogController::class, 'show'])->name('purchase-history-detail');
    Route::delete('purchase-history-destroy/{id}', [SubscriptionLogController::class, 'destroy'])->name('purchase-history-destroy');
    Route::post('purchase-history-payment-approved/{id}', [SubscriptionLogController::class, 'approval_payment'])->name('purchase-history-payment-approved');
});


// for mobile api







Route::group(['middleware' => ['XSS','DEMO', 'CurrencyLangaugeForAPI']], function () {

    Route::group(['as'=> 'user.', 'prefix' => 'user'],function (){

        Route::get('/razorpay-webview/{id}', [PaymentController::class, 'razorpay_webview'])->name('razorpay-webview');

        Route::get('/razorpay-webview-success/{id}', [PaymentController::class, 'razorpay_webview_success'])->name('razorpay-webview-success');

        Route::get('/flutterwave-webview/{id}', [PaymentController::class, 'flutterwave_webview'])->name('flutterwave-webview');
        Route::post('/flutterwave-webview-payment/{id}', [PaymentController::class, 'flutterwave_webview_payment'])->name('flutterwave-webview-payment');

        Route::get('/mollie-webview/{id}', [PaymentController::class, 'mollie_webview'])->name('mollie-webview');
        Route::get('/mollie-webview-payment', [PaymentController::class, 'mollie_webview_payment'])->name('mollie-webview-payment');


        Route::get('/paystack-webview/{id}', [PaymentController::class, 'paystack_webview'])->name('paystack-webview');
        Route::get('/paystack-webview-payment/{id}', [PaymentController::class, 'paystack_webview_payment'])->name('paystack-webview-payment');



        Route::get('/instamojo-webview/{id}', [PaymentController::class, 'instamojo_webview'])->name('instamojo-webview');
        Route::get('/instamojo-webview-payment', [PaymentController::class, 'instamojo_webview_payment'])->name('instamojo-webview-payment');


        Route::get('/paypal-webview/{id}', [PaypalController::class, 'paypal_webview'])->name('paypal-webview');
        Route::get('/paypal-webview-success', [PaypalController::class, 'paypal_webview_success'])->name('paypal-webview-success');

    });

});


Route::get('/webview-success-payment', function(){
    $notification = trans('You have successfully enrolled this package');
    return response()->json(['message' => $notification]);
})->name('webview-success-payment');

Route::get('/webview-faild-payment', function(){
    $notification = trans('Payment Faild');
    return response()->json(['message' => $notification],403);
})->name('webview-faild-payment');
