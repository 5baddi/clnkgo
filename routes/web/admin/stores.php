<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\ViewStoreController;
use BADDIServices\SourceeApp\Http\Controllers\Admin\Stores as Stores;

Route::middleware(['auth', 'admin'])
    ->prefix('admin/stores')
    ->name('admin.stores')
    ->group(function() {
        Route::get('/', Stores\IndexController::class);
        Route::post('/{store}/enable', Stores\EnableStoreController::class)->name('.enable');
        Route::post('/{store}/disable', Stores\DisableStoreController::class)->name('.disable');
        Route::get('/{store}/view', ViewStoreController::class)->name('.view');
    });