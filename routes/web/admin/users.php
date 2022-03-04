<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Admin\Users as Users;

Route::middleware(['auth', 'admin'])
    ->prefix('admin/accounts')
    ->name('admin.users')
    ->group(function() {
        Route::get('/', Users\IndexController::class);
        Route::post('/{user}/ban', Users\BanController::class)->name('.ban');
        Route::post('/{user}/password/reset', Users\ResetPasswordController::class)->name('.password.reset');
    });