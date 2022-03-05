<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\IndexController;

Route::middleware(['auth', 'has.subscription', 'client'])
    ->prefix('dashboard')
    ->group(function() {
        Route::get('/', IndexController::class)->name('dashboard');
        Route::post('/', IndexController::class)->name('dashboard.filtered');
    });