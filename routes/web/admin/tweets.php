<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Admin\Tweets\IndexController;

Route::middleware(['auth', 'is.super-admin'])
    ->name('admin.tweets')
    ->prefix('admin/tweets')
    ->group(function() {
        Route::get('/', IndexController::class);
    });