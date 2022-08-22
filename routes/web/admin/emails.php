<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\ClnkGO\Http\Controllers\Admin\Emails\IndexController;

Route::middleware(['auth', 'is.super-admin'])
    ->name('admin.emails')
    ->prefix('admin/emails')
    ->group(function() {
        Route::get('/', IndexController::class);
    });