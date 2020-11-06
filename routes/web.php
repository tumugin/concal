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
Route::resource('casts', '\App\Http\Controllers\Page\CastController')
    ->only('show');
Route::resource('groups', '\App\Http\Controllers\Page\StoreGroupController')
    ->only('index');
Route::resource('login', '\App\Http\Controllers\Page\LoginController')
    ->only('index');
