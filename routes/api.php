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

        // Portfolio
        Route::namespace('Portfolio')->group(function () {
            Route::prefix('portfolio')->name('portfolio.')->group(function () {
                // Work
                Route::post('/list', 'ListWork')->name('list');
                Route::delete('/', 'WorkController@destroy')->name('destroy');

                // Category
                Route::prefix('category')->name('category.')->group(function () {
                    Route::post('/list', 'ListCategory')->name('list');
                    Route::delete('/', 'CategoryController@destroy')->name('destroy');
                });
                Route::apiResource('category', 'CategoryController')->except([
                    'destroy'
                ]);

                // Tag
                Route::prefix('tag')->name('tag.')->group(function () {
                    Route::post('/list', 'ListTag')->name('list');
                    Route::delete('/', 'TagController@destroy')->name('destroy');
                });
                Route::apiResource('tag', 'TagController')->except([
                    'destroy'
                ]);
            });
            // Work
            Route::apiResource('portfolio', 'WorkController')->parameters([
                'portfolio' => 'work'
            ])->except([
                'destroy'
            ]);
        });

        // Blog
        Route::namespace('Blog')->group(function () {
            Route::prefix('blog')->name('blog.')->group(function () {
                // Post
                Route::post('/list', 'ListPost')->name('list');
                Route::delete('/', 'PostController@destroy')->name('destroy');
                Route::post('/restore', 'RestorePost')->name('restore');
                Route::delete('/delete', 'PermanentDestroyPost')->name('destroy.permanent');

                // Category
                Route::prefix('category')->name('category.')->group(function () {
                    Route::post('/list', 'ListCategory')->name('list');
                    Route::delete('/', 'CategoryController@destroy')->name('destroy');
                });
                Route::apiResource('category', 'CategoryController')->except([
                    'destroy'
                ]);

                // Tag
                Route::prefix('tag')->name('tag.')->group(function () {
                    Route::post('/list', 'ListTag')->name('list');
                    Route::delete('/', 'TagController@destroy')->name('destroy');
                });
                Route::apiResource('tag', 'TagController')->except([
                    'destroy'
                ]);

                // Page
                Route::prefix('page')->name('page.')->group(function () {
                    Route::post('/list', 'ListPage')->name('list');
                    Route::delete('/', 'PageController@destroy')->name('destroy');
                    Route::post('/restore', 'RestorePage')->name('restore');
                    Route::delete('/delete', 'PermanentDestroyPage')->name('destroy.permanent');
                });
                Route::apiResource('page', 'PageController')->except([
                    'destroy'
                ]);
            });
            // Post
            Route::apiResource('blog', 'PostController')->parameters([
                'blog' => 'post'
            ])->except([
                'destroy'
            ]);
        });

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
