<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Auth\Subscription\CancelController;
use BADDIServices\SourceeApp\Http\Controllers\Auth\Subscription\SubscriptionController;
use BADDIServices\SourceeApp\Http\Controllers\Auth\Subscription\BillingPaymentController;
use BADDIServices\SourceeApp\Http\Controllers\Auth\Subscription\BillingConfirmationController;

Route::middleware(['auth', 'has.subscription', 'store-owner'])
    ->name('subscription')
    ->prefix('subscription')
    ->group(function() {
        Route::get('/', SubscriptionController::class)->name('.select.pack');
        Route::get('/billing/{pack}', BillingPaymentController::class)->name('.pack.billing');
        Route::get('/billing/{pack}/confirmation', BillingConfirmationController::class)->name('.billing.confirmation');
        Route::get('/cancel', CancelController::class)->name('.cancel');
    });