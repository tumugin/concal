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

Route::resource('/', '\App\Http\Controllers\Page\TopPageController')
    ->only('index');
Route::resource('stores', '\App\Http\Controllers\Page\StoreController')
    ->only('show');
Route::resource('stores.attends', '\App\Http\Controllers\Page\Stores\AttendsController')
    ->only('index');
Route::resource('stores/{store}/attends/{year:slug}/{month:slug}', '\App\Http\Controllers\Page\Stores\Attends\Year\MonthController')
    ->only('index');
Route::resource('casts', '\App\Http\Controllers\Page\CastController')
    ->only('show');
Route::resource('groups', '\App\Http\Controllers\Page\StoreGroupController')
    ->only('index');
Route::resource('login', '\App\Http\Controllers\Page\LoginController')
    ->only('index');

// admin
Route::group(['middleware' => ['admin.allowed.host']], function () {
    Route::resource('admin', '\App\Http\Controllers\Page\AdminController')
        ->only(['index']);
    Route::group(['prefix' => 'admin'], function () {
        Route::resource('login', '\App\Http\Controllers\Page\Admin\LoginController')
            ->only(['index']);
        Route::resource('users', '\App\Http\Controllers\Page\Admin\UserController')
            ->only(['show', 'index', 'create']);
        Route::resource('admin_users', '\App\Http\Controllers\Page\Admin\AdminUserController')
            ->only(['show', 'index', 'create']);
        Route::resource('groups', '\App\Http\Controllers\Page\Admin\StoreGroupController')
            ->only(['show', 'index', 'create']);
        Route::resource('groups.stores', '\App\Http\Controllers\Page\Admin\Groups\StoreController')
            ->shallow()
            ->only(['create']);
        Route::resource('stores', '\App\Http\Controllers\Page\Admin\StoreController')
            ->only(['show', 'index', 'create']);
        Route::resource('casts', '\App\Http\Controllers\Page\Admin\CastController')
            ->only(['show', 'index', 'create']);
        Route::resource('casts.attends', '\App\Http\Controllers\Page\Admin\Casts\AttendController')
            ->shallow()
            ->only(['index']);
        Route::resource('casts.stores', '\App\Http\Controllers\Page\Admin\Casts\StoreController')
            ->shallow()
            ->only(['index']);
    });
});
