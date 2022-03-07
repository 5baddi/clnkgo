<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Responses;

use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class NewResponseController extends DashboardController
{
    public function __invoke()
    {
        return view('dashboard.responses.new', [
            'title'     => 'Add New Canned Response'
        ]);
    }
}