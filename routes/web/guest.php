<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Auth\SignInController;
use BADDIServices\SourceeApp\Http\Controllers\Auth\SignUpController;
use BADDIServices\SourceeApp\Http\Controllers\OAuth\OAuthController;
use BADDIServices\SourceeApp\Http\Controllers\Auth\ConnectController;
use BADDIServices\SourceeApp\Http\Controllers\Auth\SignOutController;
use BADDIServices\SourceeApp\Http\Controllers\Auth\CreateUserController;
use BADDIServices\SourceeApp\Http\Controllers\Auth\AuthenticateController;
use BADDIServices\SourceeApp\Http\Controllers\OAuth\OAuthCallbackController;
use BADDIServices\SourceeApp\Http\Controllers\Auth\ResetPassword as ResetPassword;

Route::middleware('guest')
    ->group(function() {
        Route::get('/connect', SignUpController::class)->name('connect');
        Route::get('/auth/connect', SignUpController::class)->name('auth.connect');
        Route::get('/signup', SignUpController::class)->name('signup');
        Route::post('/auth/signup', CreateUserController::class)->name('auth.signup');
        Route::get('/signin', SignInController::class)->name('signin');
        Route::post('/auth/signin', AuthenticateController::class)->name('auth.signin');

        Route::get('/reset', ResetPassword\IndexController::class)->name('reset');
        Route::post('/auth/token', ResetPassword\SendResetTokenController::class)->name('auth.reset.token');
        Route::get('/reset/{token}', ResetPassword\EditController::class)->name('password');
        Route::post('/auth/password', ResetPassword\ResetPasswordController::class)->name('auth.reset.password');
    });

Route::middleware(['auth'])
    ->group(function() {
        Route::get('/logout', SignOutController::class)->name('signout');
    });
