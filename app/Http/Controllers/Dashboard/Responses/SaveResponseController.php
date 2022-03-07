<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Responses;

use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class SaveResponseController extends DashboardController
{
    public function __invoke()
    {
        return view('dashboard.responses.index', [
            'title'     => 'Add New Canned Response'
        ]);
    }
}