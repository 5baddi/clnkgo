<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Admin;

use BADDIServices\SourceeApp\Http\Controllers\AdminController;

class IndexController extends AdminController
{
    public function __invoke()
    {
        return view('admin.index');
    }
}