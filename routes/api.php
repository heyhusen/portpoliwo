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

Route::namespace('API')->name('api.')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', 'HomeController@index')->name('home');

        // Category
        Route::prefix('category')->name('category.')->group(function () {
            Route::post('/list', 'CategoryController@list')->name('list');
            Route::delete('/', 'CategoryController@destroy')->name('destroy');
        });
        Route::apiResource('category', 'CategoryController')->except([
            'destroy'
        ]);

        // Tag
        Route::prefix('tag')->name('tag.')->group(function () {
            Route::post('/list', 'TagController@list')->name('list');
            Route::delete('/', 'TagController@destroy')->name('destroy');
        });
        Route::apiResource('tag', 'TagController')->except([
            'destroy'
        ]);

        // Work
        Route::prefix('work')->name('work.')->group(function () {
            Route::post('/list', 'WorkController@list')->name('list');
            Route::delete('/', 'WorkController@destroy')->name('destroy');
        });
        Route::apiResource('work', 'WorkController')->except([
            'destroy'
        ]);

        // Social Media
        Route::prefix('social-media')->name('social-media.')->group(function () {
            Route::post('/list', 'SocialMediaController@list')->name('list');
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
        Route::namespace('Setting')->prefix('setting')->name('setting.')->group(function () {
            Route::get('/', 'Site')->name('index');
            Route::post('/', 'SaveSite')->name('save');
            // :Token
            Route::prefix('token')->name('token.')->group(function () {
                Route::get('/', 'Token')->name('index');
                Route::post('/', 'CreateToken')->name('create');
                Route::delete('/{id}', 'DeleteToken')->name('delete');
            });
        });

        // Account
        Route::prefix('account')->name('account.')->group(function () {
            Route::get('/me', 'AccountController@me')->name('me');
            Route::post('/list', 'AccountController@list')->name('list');
            Route::delete('/', 'AccountController@destroy')->name('destroy');
        });
        Route::apiResource('account', 'AccountController')->parameters([
            'account' => 'user'
        ])->except([
            'destroy'
        ]);
    });
});
