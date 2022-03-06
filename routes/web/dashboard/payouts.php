<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Payouts\PayoutsController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Payouts\SendPayoutController;
    
Route::middleware(['auth', 'has.subscription', 'client'])
    ->name('dashboard.payouts')
    ->prefix('dashboard/payouts')
    ->group(function() {
        Route::get('/', PayoutsController::class);
        Route::post('/{commission}', SendPayoutController::class)->name('.send');
    });