<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\ClnkGO\Http\Controllers\Admin\Clients\IndexController;
use BADDIServices\ClnkGO\Http\Controllers\Admin\Clients\ResetClientController;
use BADDIServices\ClnkGO\Http\Controllers\Admin\Clients\RestrictClientAccessController;

Route::middleware(['auth', 'is.super-admin'])
    ->name('admin.clients')
    ->prefix('admin/clients')
    ->group(function() {
        Route::get('/', IndexController::class);
        
        Route::post('/{id}/reset/password', ResetClientController::class)->name('.password.reset');
        Route::post('/{id}/restrict', RestrictClientAccessController::class)->name('.restrict');
    });