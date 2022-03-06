<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Admin\Stats as Stats;

Route::middleware(['auth', 'admin'])
    ->get('/admin', Stats\IndexController::class)
    ->name('admin');