<?php
use Modules\Testimonial\Http\Controllers\TestimonialController;

Route::group(['as'=> 'admin.', 'prefix' => 'admin', 'middleware' => ['XSS','DEMO','auth:admin']],function (){
    Route::resource('testimonial', TestimonialController::class);
});

