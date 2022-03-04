<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Preview\CheckoutPreviewController;
    
Route::middleware(['auth', 'has.subscription', 'store-owner'])
    ->name('dashboard.preview')
    ->prefix('dashboard/preview')
    ->group(function() {
        Route::get('/checkout', CheckoutPreviewController::class)->name('.checkout');
    });