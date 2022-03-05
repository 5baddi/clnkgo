<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Keywords;

use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class KeywordsController extends DashboardController
{
    public function __invoke()
    {
        return view('dashboard.keywords.index', [
            'title'             =>  'Your Keywords 🔑',
            'keywords'          =>  $this->user->getKeywords()
        ]);
    }
}