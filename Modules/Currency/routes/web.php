<?php

use Illuminate\Support\Facades\Route;
use Modules\Currency\app\Http\Controllers\MultiCurrencyController;


Route::group(['as'=> 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'XSS','DEMO']], function () {
    Route::resource('multi-currency', MultiCurrencyController::class)->names('multi-currency');
});
