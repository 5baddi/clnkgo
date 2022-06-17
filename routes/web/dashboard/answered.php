<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Answered\IndexController;

Route::middleware(['auth', 'has.subscription'])
    ->prefix('dashboard/answered')
    ->name('dashboard.answered')
    ->group(function() {
        Route::get('/', IndexController::class);
        Route::post('/', IndexController::class)->name('.filtered');
    });