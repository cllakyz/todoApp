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
Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        // Register User
        Route::post('register', 'AuthController@register');
        // Login User
        Route::post('login', 'AuthController@login');
        // Refresh the JWT token
        Route::get('refresh', 'AuthController@refresh');

        Route::group(['middleware' => 'auth:api'], function() {
            // Get user info
            Route::get('user', 'AuthController@user');
            // Logout user
            Route::post('logout', 'AuthController@logout');
        });
    });

    Route::group(['middleware' => 'auth:api'], function() {
        Route::apiResource('todos', 'TodoController');
        Route::put('todos/{id}/complete', 'TodoController@complete');
        Route::put('todos/{id}/un_complete', 'TodoController@unComplete');
    });
});
