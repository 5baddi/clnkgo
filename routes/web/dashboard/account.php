<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Account\AccountController;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Account\UpdateAccountController;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Account\RemoveLinkedEmailController;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Account\ConfirmLinkedEmailController;
    
Route::middleware(['auth', 'has.subscription'])
    ->name('dashboard.account')
    ->prefix('dashboard/account')
    ->group(function() {
        Route::get('/', AccountController::class);
        Route::post('/', UpdateAccountController::class)->name('.save');
        Route::get('/linked/email/{id}', RemoveLinkedEmailController::class)->name('.linked-emails.remove');
        Route::get('/linked/email/confirm/{token}', ConfirmLinkedEmailController::class)->name('.linked-emails.confirm');
    });