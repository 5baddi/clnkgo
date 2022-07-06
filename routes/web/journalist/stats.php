<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\IndexController;

Route::middleware(['auth', 'has.subscription'])
    ->prefix('journalist')
    ->group(function() {
        // Route::get('/', IndexController::class)->name('journalist');
        // Route::post('/', IndexController::class)->name('journalist.filtered');
    });