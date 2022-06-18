<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\ClnkGO\Http\Controllers\Webhooks\PayPal\HandlePayPalWebhookController;

Route::middleware(['is.paypal-webhook'])
    ->prefix('webhooks/paypal')
    ->name('webhooks.paypal')
    ->group(function() {
        Route::post('/', HandlePayPalWebhookController::class);
    });