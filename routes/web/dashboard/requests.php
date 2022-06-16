<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Requests\ShowRequestController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Requests\SendDMRequestController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Requests\SendMailRequestController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Requests\MarkAsAnsweredController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Requests\MarkAsUnansweredController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Requests\RedirectToJournalistWebsiteController;
    
Route::middleware(['auth', 'has.subscription'])
    ->name('dashboard.requests')
    ->prefix('dashboard/requests')
    ->group(function() {
        Route::redirect('/', '/dashboard');

        Route::get('/{id}', ShowRequestController::class)->name('.show');
        Route::get('/redirect', RedirectToJournalistWebsiteController::class)->name('.redirect');

        Route::post('/dm/{id}', SendDMRequestController::class)->name('.dm');
        Route::post('/mail/{id}', SendMailRequestController::class)->name('.mail');
        Route::post('/answered/{id}', MarkAsAnsweredController::class)->name('.answered');
        Route::post('/unanswered/{id}', MarkAsUnansweredController::class)->name('.unanswered');
    });