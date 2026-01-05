<?php

use Modules\City\Http\Controllers\CityController;


Route::group(['as'=> 'admin.', 'prefix' => 'admin/listing', 'middleware' => ['XSS','DEMO','auth:admin']],function (){

    Route::resource('city', CityController::class);

});

