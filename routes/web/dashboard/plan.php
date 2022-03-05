<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Plan\UpgradePlanController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Account\CancelSubscriptionController;
    
Route::middleware(['auth', 'has.subscription', 'client'])
    ->name('dashboard.plan')
    ->prefix('dashboard/plan')
    ->group(function() {
        Route::get('/', function() {
            return redirect()->route('dashboard.account', ['tab' => 'plan']);
        });

        Route::get('/upgrade', UpgradePlanController::class)->name('.upgrade');
        Route::get('/cancel', CancelSubscriptionController::class)->name('.cancel');
    });