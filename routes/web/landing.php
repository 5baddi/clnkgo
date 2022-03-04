<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

use Illuminate\Support\Facades\Route;
use BADDIServices\SourceeApp\Http\Controllers\LandingPageController;

Route::get('/', LandingPageController::class)->name('landing')->middleware(['signin.with.app']);

Route::redirect('/guide', '/', 301)->name('guide');
Route::redirect('/guide/affiliate/setup', config('baddi.guide_setup', '/'), 301)->name('guide.affiliate.setup');
Route::get('/privacy.html', [LandingPageController::class, 'privacy'])->name('privacy');
Route::redirect('/termsofservice.html', '/', 301)->name('termsofservice');
