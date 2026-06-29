<?php

use Modules\Car\Http\Controllers\CarController;
use Modules\Car\Http\Controllers\CarPartController;
use Modules\Car\Http\Controllers\Frontend\CarController as FrontendCarController;
use Modules\Car\Http\Controllers\Frontend\FrontendCarPartController;


Route::group(['middleware' => ['XSS','DEMO']], function () {

    Route::group(['as'=> 'admin.', 'prefix' => 'admin/listing', 'middleware' => ['auth:admin']],function (){

        Route::resource('car', CarController::class);
        Route::resource('car-part', CarPartController::class);
        Route::delete('delete-car-part-gallery/{id}', [CarPartController::class, 'deleteGallery'])->name('delete-car-part-gallery');
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

            Route::post('car/{car}/toggle-status', [FrontendCarController::class, 'toggleStatus'])->name('car.toggle-status');

            Route::post('car/motorcheck/lookup', [FrontendCarController::class, 'motorcheck_lookup'])->name('car.motorcheck.lookup');

            Route::get('car-bulk-upload', [FrontendCarController::class, 'bulk_upload_form'])->name('car.bulk-upload.form');
            Route::get('car-bulk-upload/sample', [FrontendCarController::class, 'bulk_upload_sample'])->name('car.bulk-upload.sample');
            Route::post('car-bulk-upload', [FrontendCarController::class, 'bulk_upload_store'])->name('car.bulk-upload.store');

            Route::resource('car-part', FrontendCarPartController::class);

            Route::post('car-part/{carPart}/toggle-status', [FrontendCarPartController::class, 'toggleStatus'])->name('car-part.toggle-status');
            Route::delete('car-part-gallery/{id}', [FrontendCarPartController::class, 'deleteGallery'])->name('car-part-gallery.delete');

            Route::get('car-gallery/{id}', [FrontendCarController::class, 'car_gallery'])->name('car-gallery');
            Route::post('upload-gallery/{id}', [FrontendCarController::class, 'upload_car_gallery'])->name('upload-gallery');
            Route::delete('delete-gallery/{id}', [FrontendCarController::class, 'delete_car_gallery'])->name('delete-gallery');

        });
    });


});

