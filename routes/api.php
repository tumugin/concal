<?php

use Illuminate\Http\Request;
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

Route::post('/login', '\App\Http\Controllers\Api\ApiAuthController@login');

// user apis
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/token/revoke', '\App\Http\Controllers\Api\ApiAuthController@revokeTokens');
});

// admin apis
Route::group(['middleware' => ['auth:api', 'can:has-admin-privilege']], function () {
    // info
    Route::get('/admin/info', '\App\Http\Controllers\Api\Admin\AdminSystemInfoController@getSystemInfo')
        ->name('api.admin.info');
    // users
    Route::get('/admin/users', '\App\Http\Controllers\Api\Admin\AdminUserController@getAllUsers')
        ->name('api.admin.users');
    Route::post('/admin/users', '\App\Http\Controllers\Api\Admin\AdminUserController@addUser');
    Route::get('/admin/users/{userId}', '\App\Http\Controllers\Api\Admin\AdminUserController@getUser');
    Route::delete('/admin/users/{userId}', '\App\Http\Controllers\Api\Admin\AdminUserController@deleteUser');
    Route::patch('/admin/users/{userId}', '\App\Http\Controllers\Api\Admin\AdminUserController@editUser');
    // stores
    Route::get('/admin/stores', '\App\Http\Controllers\Api\Admin\AdminStoreController@getAllStores');
    Route::post('/admin/stores', '\App\Http\Controllers\Api\Admin\AdminStoreController@addStore');
    Route::get('/admin/stores/{storeId}', '\App\Http\Controllers\Api\Admin\AdminStoreController@getStore');
    Route::delete('/admin/stores/{storeId}', '\App\Http\Controllers\Api\Admin\AdminStoreController@deleteStore');
    Route::patch('/admin/stores/{storeId}', '\App\Http\Controllers\Api\Admin\AdminStoreController@editStore');
    // store group
    Route::get('/admin/groups', '\App\Http\Controllers\Api\Admin\AdminStoreGroupController@getAllStoreGroups');
    Route::post('/admin/groups', '\App\Http\Controllers\Api\Admin\AdminStoreGroupController@addStoreGroup');
    Route::get('/admin/groups/{storeGroupId}', '\App\Http\Controllers\Api\Admin\AdminStoreGroupController@getStoreGroup');
    Route::delete('/admin/groups/{storeGroupId}', '\App\Http\Controllers\Api\Admin\AdminStoreGroupController@deleteStoreGroup');
    Route::patch('/admin/groups/{storeGroupId}', '\App\Http\Controllers\Api\Admin\AdminStoreGroupController@editStoreGroup');
});
