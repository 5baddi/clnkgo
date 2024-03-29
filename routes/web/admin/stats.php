<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\ClnkGO\Http\Controllers\Admin\IndexController;

Route::middleware(['auth', 'is.super-admin'])
    ->prefix('admin')
    ->group(function() {
        Route::get('/', IndexController::class)->name('admin');
        // Route::post('/', IndexController::class)->name('dashboard.filtered');
    });