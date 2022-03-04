<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Customize\CustomizeController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Customize\IntegrationsController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Customize\Mails\PurchaseMailController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Customize\UpdateIntegrationsController;
use BADDIServices\SourceeApp\Http\Controllers\Dashboard\Customize\SaveCustomizeSettingController;
    
Route::middleware(['auth', 'has.subscription', 'store-owner'])
    ->name('dashboard.customize')
    ->prefix('dashboard/customize')
    ->group(function() {
        Route::get('/', CustomizeController::class);
        Route::post('/', SaveCustomizeSettingController::class)->name('.save');
        Route::get('/integrations', IntegrationsController::class)->name('.integrations');
        Route::post('/integrations', UpdateIntegrationsController::class)->name('.integrations.save');
        Route::prefix('/integrations')
            ->name('.integrations')
            ->group(function() {
                Route::get('/mails/purchase', PurchaseMailController::class)->name('.mails.purchase');
            });
    });