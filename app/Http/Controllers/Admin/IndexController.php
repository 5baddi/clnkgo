<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Admin;

use BADDIServices\ClnkGO\Http\Controllers\AdminController;
use BADDIServices\ClnkGO\Models\Marketing\MailingList;

class IndexController extends AdminController
{
    public function __invoke()
    {
        $verifiedEmails = MailingList::query()
            ->where(MailingList::IS_ACTIVE_COLUMN, 1)
            ->where(MailingList::IS_UNSUBSCRIBED_COLUMN, 0)
            ->count();
            
        $unverifiedEmails = MailingList::query()
            ->where(MailingList::IS_ACTIVE_COLUMN, 0)
            ->where(MailingList::IS_UNSUBSCRIBED_COLUMN, 0)
            ->count();

        return $this->render(
            'admin.index',
            [
                'verifiedEmails'   => $verifiedEmails,
                'unverifiedEmails' => $unverifiedEmails,
            ]
        );
    }
}