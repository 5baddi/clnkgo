<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Admin\Pages;

use BADDIServices\SourceeApp\Services\PageService;
use BADDIServices\SourceeApp\Http\Controllers\AdminController as ControllersAdminController;
use Illuminate\Http\Request;

class IndexController extends ControllersAdminController
{
    public function __construct(private PageService $pageService) {}

    public function __invoke(Request $request)
    {
        return view(
            'admin.pages.index', 
            [
                'title'     => 'Application pages',
                'pages'     => $this->pageService->paginate($request->query('page'))
            ]
        );
    }
}