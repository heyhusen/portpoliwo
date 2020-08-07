<?php

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

// Auth
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

// SPA
Route::get('/{any}', function () {
    return view('layouts.vue');
})->where('any', '.*');
