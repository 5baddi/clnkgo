<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Keywords;

use Illuminate\Http\Request;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class SaveKeywordsController extends DashboardController
{
    public function __invoke(Request $request)
    {


        return view('dashboard.payouts.index', [
            'title'             =>  'Keywords',
            'keywords'          =>  []
        ]);
    }
}