<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Responses\ResponsesController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Keywords\SaveKeywordsController;
    
Route::middleware(['auth', 'has.subscription', 'client'])
    ->name('dashboard.responses')
    ->prefix('dashboard/responses')
    ->group(function() {
        Route::get('/', ResponsesController::class);
        Route::post('/', SaveKeywordsController::class)->name('.save');
    });