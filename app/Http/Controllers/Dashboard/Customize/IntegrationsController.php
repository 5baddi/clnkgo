<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Customize;

use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class IntegrationsController extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function __invoke()
    {
        return view('dashboard.customize.integrations', [
            'title'                 =>  'Integrations',
            'setting'               =>  $this->setting,
            'store'                 =>  $this->store->slug
        ]);
    }
}