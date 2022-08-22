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
        $emails = MailingList::query()
            ->orderBy(MailingList::CREATED_AT, 'DESC')
            ->orderBy(MailingList::NAME_COLUMN, 'ASC')
            ->orderBy(MailingList::IS_ACTIVE_COLUMN, 'DESC')
            ->paginate(50);

        return $this->render(
            'admin.emails.index',
            [
                'title'     => 'Manage emails',
                'emails'    => $emails
            ]
        );
    }
}