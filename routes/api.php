<?php

use Illuminate\Support\Facades\Route;

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

Route::post('/login', 'Api\AuthController@login');

// user apis
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/token/revoke', 'Api\AuthController@revokeTokens');
    Route::get('/self', 'Api\AuthController@userInfo');
});
Route::apiResource('top_contents', 'Api\TopContentController')
    ->only(['index']);
Route::apiResource('store_groups', 'Api\StoreGroupController')
    ->only(['index', 'show']);
Route::apiResource('stores', 'Api\StoreController')
    ->only(['index', 'show']);

// admin apis
Route::group(['middleware' => ['auth:api', 'can:has-admin-privilege'], 'prefix' => 'admin'], function () {
    // info
    Route::get('info', 'Api\Admin\AdminSystemInfoController')
        ->name('api.admin.info');
    // users
    Route::apiResource('users', 'Api\Admin\AdminUserController', ['as' => 'api.admin']);
    // stores
    Route::apiResource('stores', 'Api\Admin\AdminStoreController', ['as' => 'api.admin']);
    // store group
    Route::apiResource('groups', 'Api\Admin\AdminStoreGroupController', ['as' => 'api.admin']);
    // cast
    Route::apiResource('casts', 'Api\Admin\AdminCastController', ['as' => 'api.admin']);
    // cast attend
    Route::apiResource('casts.attends', 'Api\Admin\AdminCastAttendController', ['as' => 'api.admin'])
        ->shallow();
});
