<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\ClnkGO\Http\Controllers\Admin\Tweets\IndexController;

Route::middleware(['auth', 'is.super-admin'])
    ->name('admin.tweets')
    ->prefix('admin/tweets')
    ->group(function() {
        Route::get('/', IndexController::class);
    });