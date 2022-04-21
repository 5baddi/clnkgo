<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\IndexController;

Route::middleware(['auth', 'is.super-admin'])
    ->prefix('admin')
    ->group(function() {
        Route::get('/', IndexController::class)->name('admin');
        // Route::post('/', IndexController::class)->name('dashboard.filtered');
    });