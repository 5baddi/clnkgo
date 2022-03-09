<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\IndexController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\PaginateController;

Route::middleware(['auth', 'has.subscription', 'client'])
    ->prefix('dashboard')
    ->group(function() {
        Route::get('/', IndexController::class)->name('dashboard');
        Route::post('/', IndexController::class)->name('dashboard.filtered');

        Route::get('/paginate', PaginateController::class)->name('dashboard.paginate.tweets');
    });