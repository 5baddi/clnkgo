<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Responses\ResponsesController;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Responses\NewResponseController;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Responses\CreateResponseController;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Responses\EditResponseController;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Responses\UpdateResponseController;
use BADDIServices\ClnkGO\Http\Controllers\Dashboard\Responses\DeleteResponseController;
    
Route::middleware(['auth', 'has.subscription'])
    ->name('dashboard.responses')
    ->prefix('dashboard/responses')
    ->group(function() {
        Route::get('/', ResponsesController::class);

        Route::get('/new', NewResponseController::class)->name('.new');
        Route::post('/new', CreateResponseController::class)->name('.save');
        Route::get('/edit/{id}', EditResponseController::class)->name('.edit');
        Route::post('/edit/{id}', UpdateResponseController::class)->name('.update');
        Route::delete('/delete/{id}', DeleteResponseController::class)->name('.delete');
    });