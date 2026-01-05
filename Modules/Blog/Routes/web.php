<?php

use Modules\Blog\Http\Controllers\BlogCategoryController;
use Modules\Blog\Http\Controllers\BlogController;

Route::group(['as'=> 'admin.', 'prefix' => 'admin/cms', 'middleware' => ['XSS','DEMO', 'auth:admin']],function (){

    Route::resources([
        'blog-category' => BlogCategoryController::class,
        'blog' => BlogController::class,
    ]);

    Route::controller(BlogController::class)->group(function () {
        Route::get('blog-comments', 'blog_comment')->name('blog-comments');
        Route::get('show-comment/{id}', 'show_comment')->name('show-comment');
        Route::delete('delete-blog-comment/{id}', 'destroy_comment')->name('delete-blog-comment');
        Route::put('blog-comment-status/{id}', 'blog_comment_status')->name('blog-comment-status');
    });

});

