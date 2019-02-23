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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get("posts/{post}/duplicate", ['as' => 'posts.duplicate', 'uses' => 'PostController@duplicate']);
Route::resource("posts","PostController");
Route::get("tags/{tag}/duplicate", ['as' => 'tags.duplicate', 'uses' => 'TagController@duplicate']);
Route::resource("tags","TagController");
Route::get("categories/{category}/duplicate", ['as' => 'categories.duplicate', 'uses' => 'CategoryController@duplicate']);
Route::resource("categories","CategoryController");
Route::get("comments/{comment}/duplicate", ['as' => 'comments.duplicate', 'uses' => 'CommentController@duplicate']);
Route::resource("comments","CommentController");
