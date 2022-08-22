<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Admin\Emails;

use Illuminate\Http\Request;
use BADDIServices\ClnkGO\Http\Controllers\AdminController;
use BADDIServices\ClnkGO\Models\Marketing\MailingList;

class IndexController extends AdminController
{
    public function __invoke(Request $request)
    {
        return $this->render(
            'admin.emails.index',
            [
                'title'     => 'Manage emails',
                'emails'    => MailingList::query()->orderBy(MailingList::CREATED_AT)->paginate(50)
            ]
        );
    }
}