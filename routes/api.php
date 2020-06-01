<?php

use Illuminate\Http\Request;

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

Route::namespace('API')->group(function () {
    // Auth
    Route::namespace('Auth')->prefix('auth')->group(function () {
        Route::post('login', 'LoginController@login');
        Route::post('register', 'RegisterController');

        Route::prefix('password')->group(function () {
            Route::post('email', 'ForgotPasswordController');
            Route::post('reset', 'ResetPasswordController@reset');
        });

        Route::middleware('auth:api')->group(function () {
            Route::get('user', 'LoginController@user');
            Route::get('logout', 'LoginController@logout');
        });
    });

    Route::middleware('auth:api')->group(function () {
        Route::get('/', 'HomeController@index')->name('home');

        // Category
        Route::prefix('category')->name('category.')->group(function () {
            Route::delete('/', 'CategoryController@destroy')->name('destroy');
        });
        Route::apiResource('category', 'CategoryController')->except([
            'destroy'
        ]);

        // Tag
        Route::prefix('tag')->name('tag.')->group(function () {
            Route::delete('/', 'TagController@destroy')->name('destroy');
        });
        Route::apiResource('tag', 'TagController')->except([
            'destroy'
        ]);

        // Work
        Route::prefix('work')->name('work.')->group(function () {
            Route::delete('/', 'WorkController@destroy')->name('destroy');
        });
        Route::apiResource('work', 'WorkController')->except([
            'destroy'
        ]);

        // Social Media
        Route::prefix('social-media')->name('social-media.')->group(function () {
            Route::get('/{social_media}', 'SocialMediaController@show')->name('show');
            Route::match(['put', 'patch'], '/{social_media}', 'SocialMediaController@update')->name('update');
            Route::delete('/', 'SocialMediaController@destroy')->name('destroy');
        });
        Route::apiResource('social-media', 'SocialMediaController')->parameters([
            'social_sedium' => 'social_media'
        ])->except([
            'show', 'update', 'destroy'
        ]);

        // Setting
        Route::apiResource('setting', 'SettingController');
    });
});