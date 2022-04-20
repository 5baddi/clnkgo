<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Account\AccountController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Account\UpdateAccountController;
    
Route::middleware(['auth', 'has.subscription'])
    ->name('dashboard.account')
    ->prefix('dashboard/account')
    ->group(function() {
        Route::get('/', AccountController::class);
        Route::post('/', UpdateAccountController::class)->name('.save');
    });