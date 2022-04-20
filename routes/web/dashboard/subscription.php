<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Auth\Subscription\CancelController;
use BADDIServices\SourceeApp\Http\Controllers\Auth\Subscription\BillingConfirmationController;
use BADDIServices\SourceeApp\Http\Controllers\Auth\Subscription\CheckoutController;
use BADDIServices\SourceeApp\Http\Controllers\Auth\Subscription\CancelCheckoutController;

Route::middleware(['auth', 'has.subscription'])
    ->name('subscription')
    ->prefix('subscription')
    ->group(function() {
        Route::get('/billing/{pack}', CheckoutController::class)->name('.pack.billing');
        Route::get('/billing/{pack}/confirmation', BillingConfirmationController::class)->name('.billing.confirmation');
        Route::get('/billing/{pack}/cancel', CancelCheckoutController::class)->name('.billing.cancel');

        Route::get('/cancel', CancelController::class)->name('.cancel');
    });