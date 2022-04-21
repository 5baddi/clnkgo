<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Admin\Settings\IndexController;

Route::middleware(['auth', 'is.super-admin'])
    ->name('admin.settings')
    ->prefix('admin/settings')
    ->group(function() {
        Route::get('/', IndexController::class);
    });