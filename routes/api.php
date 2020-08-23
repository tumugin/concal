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
    Route::get('/admin/info', '\App\Http\Controllers\Api\Admin\AdminSystemInfoController@getSystemInfo')
        ->name('api.admin.info');
    Route::get('/admin/users', '\App\Http\Controllers\Api\Admin\AdminUserController@getAllUsers')
        ->name('api.admin.users');
    Route::post('/admin/users', '\App\Http\Controllers\Api\Admin\AdminUserController@addUser');
    Route::get('/admin/users/{userId}', '\App\Http\Controllers\Api\Admin\AdminUserController@getUser');
    Route::delete('/admin/users/{userId}', '\App\Http\Controllers\Api\Admin\AdminUserController@deleteUser');
    Route::patch('/admin/users/{userId}', '\App\Http\Controllers\Api\Admin\AdminUserController@editUser');
});
