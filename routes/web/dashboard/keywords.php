<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Keywords\KeywordsController;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Keywords\SaveKeywordsController;
    
Route::middleware(['auth', 'has.subscription'])
    ->name('dashboard.keywords')
    ->prefix('dashboard/keywords')
    ->group(function() {
        Route::get('/', KeywordsController::class);
        Route::post('/', SaveKeywordsController::class)->name('.save');
    });