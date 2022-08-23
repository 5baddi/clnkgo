<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Admin\Emails;

use BADDIServices\ClnkGO\App;
use Illuminate\Http\Request;
use BADDIServices\ClnkGO\Http\Controllers\AdminController;
use BADDIServices\ClnkGO\Models\Marketing\MailingList;

class IndexController extends AdminController
{
    public function __invoke(Request $request)
    {
        $emails = MailingList::query()
            ->where(MailingList::IS_ACTIVE_COLUMN, 1)
            ->orderBy(MailingList::NAME_COLUMN, 'ASC')
            ->orderBy(MailingList::CREATED_AT, 'DESC')
            ->paginate(App::PAGINATION_LIMIT);

        $verifiedEmails = MailingList::query()
            ->where(MailingList::IS_ACTIVE_COLUMN, 1)
            ->where(MailingList::IS_UNSUBSCRIBED_COLUMN, 0)
            ->count();
            
        $unverifiedEmails = MailingList::query()
            ->where(MailingList::IS_ACTIVE_COLUMN, 0)
            ->where(MailingList::IS_UNSUBSCRIBED_COLUMN, 0)
            ->count();

        return $this->render(
            'admin.emails.index',
            [
                'title'            => 'Manage emails',
                'emails'           => $emails,
                'verifiedEmails'   => $verifiedEmails,
                'unverifiedEmails' => $unverifiedEmails,
            ]
        );
    }
}