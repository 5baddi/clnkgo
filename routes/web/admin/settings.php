<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Admin\Settings as Settings;

Route::middleware(['auth', 'admin'])
    ->prefix('admin/settings')
    ->name('admin.settings')
    ->group(function() {
        Route::get('/', Settings\IndexController::class);
        Route::post('/', Settings\UpdateSettingsController::class)->name('.update');
    });