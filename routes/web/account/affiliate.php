<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Affiliate\Dashboard\AnalyticsController;
use BADDIServices\SourceeApp\Http\Controllers\Affiliate\Inscription\CreateAccountController;
use BADDIServices\SourceeApp\Http\Controllers\Affiliate\Inscription\SignUpController as AffiliateSignUpController;

Route::name('affiliate.')
    ->group(function() {
        Route::prefix('affiliate')
            ->middleware('guest')
            ->group(function() {
                Route::redirect('/', '/', 301);

                Route::get('/{store}', AffiliateSignUpController::class);
                Route::post('/{store}/signup', CreateAccountController::class)->name('signup');
            });

        Route::prefix('mya')
            ->middleware(['auth', 'is.affiliate'])
            ->group(function () {
                Route::get('/', AnalyticsController::class)->name('analytics');
            });
    });