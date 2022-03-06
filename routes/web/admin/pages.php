<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Admin\Pages as Pages;

Route::middleware(['auth', 'admin'])
    ->prefix('admin/pages')
    ->name('admin.pages')
    ->group(function() {
        Route::get('/', Pages\IndexController::class);
        Route::get('/create', Pages\CreatePageController::class)->name('.create');
        Route::post('/create', Pages\StorePageController::class)->name('.save');
    });