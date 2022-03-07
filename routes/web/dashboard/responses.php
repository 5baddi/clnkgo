<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Responses\ResponsesController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Responses\NewResponseController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Responses\SaveResponseController;
    
Route::middleware(['auth', 'has.subscription', 'client'])
    ->name('dashboard.responses')
    ->prefix('dashboard/responses')
    ->group(function() {
        Route::get('/', ResponsesController::class);

        Route::get('/new', NewResponseController::class)->name('.new');
        Route::post('/new', SaveResponseController::class)->name('.save');
    });