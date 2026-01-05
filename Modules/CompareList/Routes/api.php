<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\CompareList\Http\Controllers\CompareListController;

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

        Route::resource('comparelist', CompareListController::class)->names('comparelist');

    });
});


