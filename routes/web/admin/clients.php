<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Admin\Clients\IndexController;
use BADDIServices\SourceeApp\Http\Controllers\Admin\Clients\ResetClientController;

Route::middleware(['auth', 'is.super-admin'])
    ->name('admin.clients')
    ->prefix('admin/clients')
    ->group(function() {
        Route::get('/', IndexController::class);
        
        Route::post('/{id}/reset/password', ResetClientController::class)->name('.password.reset');
    });