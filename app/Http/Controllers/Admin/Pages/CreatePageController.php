<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Admin\Pages;

use BADDIServices\SourceeApp\Http\Controllers\AdminController as ControllersAdminController;

class CreatePageController extends ControllersAdminController
{
    public function __invoke()
    {
        return view(
            'admin.pages.create', 
            [
                'title'     => 'Create new page',
            ]
        );
    }
}