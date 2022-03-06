<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Admin\Settings;

use BADDIServices\SourceeApp\Http\Controllers\AdminController as ControllersAdminController;

class IndexController extends ControllersAdminController
{
    public function __invoke()
    {
        return view('admin.settings.index', [
            'title'     =>  'Application settings'
        ]);
    }
}