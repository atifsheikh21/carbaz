<?php

use Modules\Feature\Http\Controllers\FeatureController;

Route::group(['as'=> 'admin.', 'prefix' => 'admin/listing', 'middleware' => ['XSS','DEMO','auth:admin']],function (){

    Route::resource('feature', FeatureController::class);
});


