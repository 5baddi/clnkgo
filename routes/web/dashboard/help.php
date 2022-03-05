<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\HelpController;
    
Route::middleware(['auth', 'has.subscription', 'client'])
    ->name('dashboard.help')
    ->prefix('dashboard/help')
    ->group(function() {
        Route::get('/', HelpController::class);
    });