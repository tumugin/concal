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
Route::apiResource('stores.attends', 'Api\Stores\AttendsController')
    ->only(['index']);
Route::apiResource('casts', 'Api\CastController')
    ->only(['show']);
Route::apiResource('casts.attends', 'Api\Casts\AttendsController')
    ->only(['index']);

// admin apis
Route::group(['prefix' => 'admin'], function () {
    Route::post('/login', 'Api\Admin\AdminAuthController@login');

    Route::group(['middleware' => ['auth:admin_api', 'can:has-admin-privilege']], function () {
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
        // self
        Route::post('/token/revoke', 'Api\Admin\AdminAuthController@revokeTokens');
        Route::get('/self', 'Api\Admin\AdminAuthController@userInfo');
    });
});
