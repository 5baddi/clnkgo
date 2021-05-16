<?php

/**
 * Social Rocket
 *
 * @copyright   Copyright (c) 2021, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SocialRocket\Http\Controllers\AffiliateController;
use BADDIServices\SocialRocket\Http\Controllers\Auth\SignInController;
use BADDIServices\SocialRocket\Http\Controllers\Auth\SignUpController;
use BADDIServices\SocialRocket\Http\Controllers\LandingPageController;
use BADDIServices\SocialRocket\Http\Controllers\OAuth\OAuthController;
use BADDIServices\SocialRocket\Http\Controllers\Auth\ConnectController;
use BADDIServices\SocialRocket\Http\Controllers\Auth\SignOutController;
use BADDIServices\SocialRocket\Http\Controllers\Dashboard\HelpController;
use BADDIServices\SocialRocket\Http\Controllers\Auth\CreateUserController;
use BADDIServices\SocialRocket\Http\Controllers\Dashboard\IndexController;
use BADDIServices\SocialRocket\Http\Controllers\Auth\AuthenticateController;
use BADDIServices\SocialRocket\Http\Controllers\Dashboard\PayoutsController;
use BADDIServices\SocialRocket\Http\Controllers\Dashboard\ActivityController;
use BADDIServices\SocialRocket\Http\Controllers\OAuth\OAuthCallbackController;
use BADDIServices\SocialRocket\Http\Controllers\Dashboard\Account\AccountController;
use BADDIServices\SocialRocket\Http\Controllers\Dashboard\Plan\UpgradePlanController;
use BADDIServices\SocialRocket\Http\Controllers\Dashboard\Settings\SettingsController;
use BADDIServices\SocialRocket\Http\Controllers\Dashboard\ActivityMarkAsReadController;
use BADDIServices\SocialRocket\Http\Controllers\Dashboard\Customize\CustomizeController;
use BADDIServices\SocialRocket\Http\Controllers\Auth\Subscription\SubscriptionController;
use BADDIServices\SocialRocket\Http\Controllers\Dashboard\Account\UpdateAccountController;
use BADDIServices\SocialRocket\Http\Controllers\Auth\Subscription\BillingPaymentController;
use BADDIServices\SocialRocket\Http\Controllers\Dashboard\Customize\IntegrationsController;
use BADDIServices\SocialRocket\Http\Controllers\Dashboard\Preview\CheckoutPreviewController;
use BADDIServices\SocialRocket\Http\Controllers\Dashboard\Settings\UpdateSettingsController;
use BADDIServices\SocialRocket\Http\Controllers\Auth\Subscription\BillingConfirmationController;
use BADDIServices\SocialRocket\Http\Controllers\Dashboard\Customize\UpdateIntegrationsController;
use BADDIServices\SocialRocket\Http\Controllers\Dashboard\Customize\SaveCustomizeSettingController;

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

Route::get('/', LandingPageController::class);
Route::redirect('/guide', '/', 301)->name('guide');

Route::get('/affiliate/{store}', AffiliateController::class)->name('affiliate');
Route::post('/affiliate', AffiliateController::class)->name('affiliate.signup');

Route::middleware('guest')
    ->group(function() {
        Route::get('/connect', ConnectController::class)->middleware(['has.store', 'app.connected'])->name('connect');
        Route::post('/connect', OAuthController::class)->name('oauth.connect');
        Route::get('/oauth/callback', OAuthCallbackController::class)->name('oauth.callback');

        Route::get('/signup', SignUpController::class)->middleware(['has.store', 'app.notconnected'])->name('signup');
        Route::post('/auth/signup', CreateUserController::class)->name('auth.signup');
        Route::get('/signin', SignInController::class)->name('signin');
        Route::post('/auth/signin', AuthenticateController::class)->name('auth.signin');
    });

Route::middleware(['auth', 'has.subscription'])
    ->name('subscription')
    ->prefix('subscription')
    ->group(function() {
        Route::get('/', SubscriptionController::class)->name('.select.pack');
        Route::get('/billing/{pack}', BillingPaymentController::class)->name('.pack.billing');
        Route::get('/billing/{pack}/confirmation', BillingConfirmationController::class)->name('.billing.confirmation');
    });

Route::middleware(['auth', 'has.subscription'])
    ->name('dashboard')
    ->prefix('dashboard')
    ->group(function() {
        Route::get('/', IndexController::class);

        Route::get('/customize', CustomizeController::class)->name('.customize');
        Route::post('/customize', SaveCustomizeSettingController::class)->name('.customize.save');
        Route::get('/customize/integrations', IntegrationsController::class)->name('.customize.integrations');
        Route::post('/customize/integrations', UpdateIntegrationsController::class)->name('.customize.integrations.save');
        
        Route::get('/payouts', PayoutsController::class)->name('.payouts');

        Route::get('/account', AccountController::class)->name('.account');
        Route::post('/account', UpdateAccountController::class)->name('.account.save');

        Route::get('/settings', SettingsController::class)->name('.settings');
        Route::post('/settings', UpdateSettingsController::class)->name('.settings.save');

        Route::get('/help', HelpController::class)->name('.help');

        Route::get('/activity', ActivityController::class)->name('.activity');
        Route::get('/activity/{notification}', ActivityMarkAsReadController::class)->name('.activity.read');

        Route::get('/preview/checkout', CheckoutPreviewController::class)->name('.preview.checkout');

        Route::get('/plan', function() {
            return redirect()->route('dashboard.account', ['tab' => 'plan']);
        })->name('.plan');
        Route::get('/plan/upgrade', UpgradePlanController::class)->name('.plan.upgrade');
        Route::get('/plan/cancel', UpgradePlanController::class)->name('.plan.cancel');

        Route::get('/logout', SignOutController::class)->name('.signout');
    });