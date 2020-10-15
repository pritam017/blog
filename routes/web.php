<?php

use Facade\FlareClient\View;
use Illuminate\Support\Facades\Route;

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



Auth::routes();
Route::get('/',[ App\Http\Controllers\HomeController::class, 'index'])->name('welcome');
Route::get('/post/{slug}',[ App\Http\Controllers\PostController::class, 'details'])->name('post.details');
Route::get('/category/{slug}',[ App\Http\Controllers\PostController::class, 'postByCategory'])->name('postByCategory');
Route::get('/tag/{slug}',[ App\Http\Controllers\PostController::class, 'postByTag'])->name('postByTag');
Route::get('/posts',[ App\Http\Controllers\PostController::class, 'index'])->name('all.post');
Route::get('/profile/{username}',[ App\Http\Controllers\AuthorController::class, 'profile'])->name('author.profile');
Route::post('/subscriber',[ App\Http\Controllers\SubscriberController::class, 'store'])->name('subscriber.store');
Route::post('/search',[ App\Http\Controllers\SearchController::class, 'index'])->name('search');
Route::group(['middleware' => ['auth']], function() {

    Route::post('/favorite/{post}',[ App\Http\Controllers\FavoriteController::class, 'favorite'])->name('favorite.post');
    Route::post('/comment/{post}',[ App\Http\Controllers\CommentController::class, 'store'])->name('comment.store');
});

Route::group(['as'=>'admin.','prefix' => 'admin', 'middleware'=>['auth','admin']], function() {

    Route::resource('/dashboard', App\Http\Controllers\Admin\DashboardController::class);
    Route::resource('/tag', App\Http\Controllers\Admin\TagController::class);
    Route::resource('/category', App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('/post', App\Http\Controllers\Admin\PostController::class);
    Route::put('/approve/{id}',[ App\Http\Controllers\Admin\PostController::class, 'approve'])->name('approve');
    Route::get('/pending/post',[ App\Http\Controllers\Admin\PostController::class, 'pending'])->name('pending');
    Route::get('/subscriber',[ App\Http\Controllers\Admin\SubscriberController::class, 'index'])->name('subscriber.index');

    Route::get('/favorite',[ App\Http\Controllers\Admin\FavoriteController::class, 'index'])->name('favorite.index');


    Route::resource('/author', App\Http\Controllers\Admin\AuthorController::class);

    Route::get('/settings',[ App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::put('/update-profile',[ App\Http\Controllers\Admin\SettingsController::class, 'updateProfile'])->name('updateProfile');
    Route::put('/update-password',[ App\Http\Controllers\Admin\SettingsController::class, 'updatePassword'])->name('updatePassword');
    Route::delete('/destroy/{id}',[ App\Http\Controllers\Admin\SubscriberController::class, 'destroy'])->name('subscriber.destroy');
});
Route::group(['as'=>'author.','prefix' => 'author', 'middleware'=>['auth','author']], function() {
    Route::resource('/dashboard', App\Http\Controllers\Author\DashboardController::class);
    Route::resource('/post', App\Http\Controllers\Author\PostController::class);
    Route::get('/favorite',[ App\Http\Controllers\Author\FavoriteController::class, 'index'])->name('favorite.index');
    Route::get('/settings',[ App\Http\Controllers\Author\SettingsController::class, 'index'])->name('settings');
    Route::put('/update-profile',[ App\Http\Controllers\Author\SettingsController::class, 'updateProfile'])->name('updateProfile');
    Route::put('/update-password',[ App\Http\Controllers\Author\SettingsController::class, 'updatePassword'])->name('updatePassword');
});



view()->composer('layouts.frontend.partial.footer', function($view) {
    $categories = App\Models\Category::all();
    $view->with('categories', $categories);
});
