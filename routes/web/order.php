<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\OrderStatusScriptController;

Route::middleware(['cors'])->group(function() {
    Route::get('/order_status.js', OrderStatusScriptController::class);
});