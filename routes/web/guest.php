<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use BADDIServices\ClnkGO\Http\Controllers\Auth\SignInController;
use BADDIServices\ClnkGO\Http\Controllers\Auth\SignUpController;
use BADDIServices\ClnkGO\Http\Controllers\Auth\SignOutController;
use BADDIServices\ClnkGO\Http\Controllers\Auth\CreateUserController;
use BADDIServices\ClnkGO\Http\Controllers\Auth\AuthenticateController;
use BADDIServices\ClnkGO\Http\Controllers\Auth\ConfirmEmailController;
use BADDIServices\ClnkGO\Http\Controllers\Auth\ResetPassword as ResetPassword;
use BADDIServices\ClnkGO\Http\Controllers\CPALead\CPALeadUnsubscribeController;
use BADDIServices\ClnkGO\Http\Controllers\CPALead\CPALeadRedirectToOfferController;

Route::get('/', function () {
    return redirect(env('SAAS_URL', 'https://clnkgo.com'), Response::HTTP_PERMANENTLY_REDIRECT);
})->name('home');

Route::get('/offers/unsubscribe', CPALeadUnsubscribeController::class)->name('cpalead.unsubscribe');
Route::get('/offers/redirect', CPALeadRedirectToOfferController::class)->name('cpalead.redirect');

Route::middleware('basic.auth')
    ->group(function () {
        Route::get('/migrate', function () {
            Artisan::call('migrate:fresh');
            Artisan::call('db:seed');
        });
        
        Route::get('/fetch', function () {
            Artisan::call('twitter:latest-tweets');
        });
        
        Route::get('/keywords', function () {
            Artisan::call('app:update-most-used-keywords');
        });
        
        Route::get('/mails', function () {
            Artisan::call('mail:new-request');
        });
    });

Route::middleware('guest')
    ->group(function() {
        Route::get('/signup', SignUpController::class)->name('signup');
        Route::post('/auth/signup', CreateUserController::class)->name('auth.signup');
        Route::get('/signin', SignInController::class)->name('signin');
        Route::post('/auth/signin', AuthenticateController::class)->name('auth.signin');

        Route::get('/auth/confirm/{token}', ConfirmEmailController::class)->name('auth.confirm-email');

        Route::get('/reset', ResetPassword\IndexController::class)->name('reset');
        Route::post('/auth/token', ResetPassword\SendResetTokenController::class)->name('auth.reset.token');
        Route::get('/reset/{token}', ResetPassword\EditController::class)->name('password');
        Route::post('/auth/password', ResetPassword\ResetPasswordController::class)->name('auth.reset.password');
    });

Route::middleware(['auth'])
    ->group(function() {
        Route::get('/logout', SignOutController::class)->name('signout');
    });

Route::get('/webceo', function () {
    return view('webceo.signup');
});

Route::post('/webceo/signup', function (Request $request) {
    $client = new Client([
        'base_uri'      => 'https://online.webceo.com/api/',
        'debug'         => false,
        'http_errors'   => false,
    ]);

    $response = $client
        ->request(
            'POST',
            '', 
            [
                'headers'           => [
                    'Accept'        => 'application/json',
                ],
                'body'              => json_encode([
                    'method'        => 'add_user',
                    'key'           => '6eb617271c3c1fc349',
                    'data'          => [
                        'user'                  => $request->input('email'),
                        'password'              => $request->input('password'),
                        'send_credentials'      => 0,
                        'share_demo_project'    => 1,
                    ]
                ])
            ]
        );

    $code = explode('@', $request->input('email'))[0] || '';

    if ($response->getStatusCode() === Response::HTTP_OK || empty($code)) {
        return redirect(sprintf('https://go.seokits.co/accounts/domain/login/?code=%s', $code));
    }

    return abort(400);
});

Route::post('/webceo/callback', function (Request $request) {
    $client = new Client([
        'base_uri'      => 'https://online.webceo.com/api/',
        'debug'         => false,
        'http_errors'   => false,
    ]);

    $response = $client
        ->request(
            'POST',
            '', 
            [
                'headers'           => [
                    'Accept'        => 'application/json',
                ],
                'body'              => json_encode([
                    'method'        => 'get_users',
                    'key'           => '6eb617271c3c1fc349',
                ])
            ]
        );

    if ($response->getStatusCode() === Response::HTTP_OK) {
        $results = json_decode($response->getBody(), true);
        dd($results['data'][0]['email']);
    }

    return abort(401);
});