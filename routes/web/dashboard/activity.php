<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Activity\ActivityController;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Activity\ActivityMarkAsReadController;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Activity\ActivityMarkAllAsReadController;
    
Route::middleware(['auth', 'has.subscription'])
    ->name('dashboard.activity')
    ->prefix('dashboard/activity')
    ->group(function() {
        Route::get('/', ActivityController::class);
        Route::get('/read', ActivityMarkAllAsReadController::class)->name('.read.all');
        Route::get('/{notification}', ActivityMarkAsReadController::class)->name('.read');
    });