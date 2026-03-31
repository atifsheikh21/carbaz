<?php

use Modules\Car\Http\Controllers\CarController;
use Modules\Car\Http\Controllers\CarPartController;
use Modules\Car\Http\Controllers\Frontend\CarController as FrontendCarController;
use Modules\Car\Http\Controllers\Frontend\FrontendCarPartController;


Route::group(['middleware' => ['XSS','DEMO']], function () {

    Route::group(['as'=> 'admin.', 'prefix' => 'admin/listing', 'middleware' => ['auth:admin']],function (){

        Route::resource('car', CarController::class);
        Route::resource('car-part', CarPartController::class);
        Route::get('awaiting-car', [CarController::class, 'awaiting_car'])->name('awaiting-car');
        Route::get('featured-car', [CarController::class, 'featured_car'])->name('featured-car');
        Route::get('select-car-purpose', [CarController::class, 'select_car_purpose'])->name('select-car-purpose');
        Route::get('car-gallery/{id}', [CarController::class, 'car_gallery'])->name('car-gallery');
        Route::post('upload-gallery/{id}', [CarController::class, 'upload_car_gallery'])->name('upload-gallery');
        Route::delete('delete-gallery/{id}', [CarController::class, 'delete_car_gallery'])->name('delete-gallery');

        Route::put('car-approval/{id}', [CarController::class, 'car_approval'])->name('car-approval');
        Route::put('car-featured/{id}', [CarController::class, 'car_featured'])->name('car-featured');
        Route::put('car-removed-featured/{id}', [CarController::class, 'car_removed_featured'])->name('car-removed-featured');

        Route::get('review-list', [CarController::class, 'review_list'])->name('review-list');
        Route::get('review-detail/{id}', [CarController::class, 'review_detail'])->name('review-detail');
        Route::delete('review-delete/{id}', [CarController::class, 'review_delete'])->name('review-delete');
        Route::put('review-approval/{id}', [CarController::class, 'review_approval'])->name('review-approval');

    });

    Route::group(['middleware' => ['HtmlSpecialchars', 'auth:web']], function () {

        Route::group(['as'=> 'user.', 'prefix' => 'user'],function (){

            Route::get('select-car-purpose', [FrontendCarController::class, 'select_car_purpose'])->name('select-car-purpose');

            Route::resource('car', FrontendCarController::class);

            Route::post('car/motorcheck/lookup', [FrontendCarController::class, 'motorcheck_lookup'])->name('car.motorcheck.lookup');

            Route::resource('car-part', FrontendCarPartController::class);

            Route::get('car-gallery/{id}', [FrontendCarController::class, 'car_gallery'])->name('car-gallery');
            Route::post('upload-gallery/{id}', [FrontendCarController::class, 'upload_car_gallery'])->name('upload-gallery');
            Route::delete('delete-gallery/{id}', [FrontendCarController::class, 'delete_car_gallery'])->name('delete-gallery');

        });
    });


});

