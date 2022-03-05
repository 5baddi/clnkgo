<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Settings\SettingsController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Settings\UpdateSettingsController;
    
Route::middleware(['auth', 'has.subscription', 'client'])
    ->name('dashboard.settings')
    ->prefix('dashboard/settings')
    ->group(function() {
        Route::get('/', SettingsController::class);
        Route::post('/', UpdateSettingsController::class)->name('.save');
    });