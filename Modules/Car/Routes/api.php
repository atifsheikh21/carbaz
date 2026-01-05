<?php

use Illuminate\Http\Request;
use Modules\Car\Http\Controllers\API\CarController;

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


    Route::group(['middleware' => ['HtmlSpecialchars', 'auth:api']], function () {

        Route::group(['as'=> 'user.', 'prefix' => 'user'],function (){

            Route::get('select-car-purpose', [CarController::class, 'select_car_purpose'])->name('select-car-purpose');

            Route::resource('car', CarController::class);
            Route::post('car-key-feature/{id}', [CarController::class, 'car_key_feature'])->name('car-key-feature');
            Route::post('car-feature/{id}', [CarController::class, 'car_feature'])->name('car-feature');
            Route::post('car-address/{id}', [CarController::class, 'car_address'])->name('car-address');
            Route::post('video-images/{id}', [CarController::class, 'video_images'])->name('video-images');
            Route::delete('image-delete/{id}', [CarController::class, 'image_delete'])->name('image-delete');
            Route::post('request-to-publish/{id}', [CarController::class, 'request_to_publish'])->name('request-to-publish');

            Route::get('car-gallery/{id}', [CarController::class, 'car_gallery'])->name('car-gallery');
            Route::post('upload-gallery/{id}', [CarController::class, 'upload_car_gallery'])->name('upload-gallery');
            Route::delete('delete-gallery/{id}', [CarController::class, 'delete_car_gallery'])->name('delete-gallery');

        });
    });


});
