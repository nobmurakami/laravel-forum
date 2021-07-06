<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'ThreadController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('threads', 'ThreadController');
Route::get('/posts/{replyTo}/reply', 'PostController@reply')->name('posts.reply');
Route::resource('threads.posts', 'PostController')->shallow()->only([
    'create', 'store', 'edit', 'update', 'destroy'
]);
Route::delete('post_images/{post}', 'PostController@destroyImage')->name('posts.image.destroy');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function (){
    Route::get('/', 'AdminController@home')->name('home');
    Route::resource('users', 'UserController')->only([
        'index'
    ]);
});
