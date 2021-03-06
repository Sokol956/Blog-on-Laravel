<?php

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

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::resource('rest', 'App\Http\Controllers\RestTestController')->names('restTest');

Route::group(['prefix' => 'digging_deeper',], function () {
    Route::get('collections', 'DiggingDeeperController@collections')
        ->name('digging_deeper.collections');
});

Route::group(['namespace' => 'App\Http\Controllers\Blog', 'prefix' => 'blog'], function () {
    Route::resource('posts', PostController::class)->names('blog.posts');
});

// Админка блога
$groupData = [
    'namespace' => 'App\Http\Controllers\Blog\Admin',
    'prefix'    => 'admin/blog',
];
Route::group($groupData, function() {
    //BlogCategory
    $methods = ['index', 'edit', 'store', 'update', 'create'];
    Route::resource('categories', 'CategoryController')
        ->only($methods)
        ->names('blog.admin.categories');
    //BlogPost
    Route::resource('posts', 'PostController')
        ->except(['show'])
        ->names('blog.admin.posts');
});

