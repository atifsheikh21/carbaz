<?php

use Illuminate\Http\Request;
use Modules\ContactMessage\Http\Controllers\API\ContactMessageController;

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


Route::group(['middleware' => ['XSS','HtmlSpecialchars','DEMO']], function () {
    Route::post('store-contact-message', [ContactMessageController::class, 'store_contact_message'])->name('store-contact-message');
});
