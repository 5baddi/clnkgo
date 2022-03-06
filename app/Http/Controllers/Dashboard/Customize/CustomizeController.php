<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Customize;

use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class CustomizeController extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __invoke()
    {
        return view('dashboard.customize.index', [
            'title'                 =>  'Customize',
            'setting'               =>  $this->setting
        ]);
    }
}