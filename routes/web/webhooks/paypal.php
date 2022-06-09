<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Bookmarked\IndexController;

Route::middleware(['is.paypal-webhook'])
    ->prefix('webhooks/paypal')
    ->name('webhooks.paypal')
    ->group(function() {
        Route::get('/', IndexController::class);
        Route::post('/', IndexController::class)->name('.filtered');
    });