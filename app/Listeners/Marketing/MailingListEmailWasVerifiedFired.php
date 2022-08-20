<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Listeners\Marketing;

use Illuminate\Contracts\Queue\ShouldQueue;
use BADDIServices\ClnkGO\Models\Marketing\MailingList;
use BADDIServices\ClnkGO\Events\Marketing\MailingListEmailWasVerified;

class MailingListEmailWasVerifiedFired implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    public function handle(MailingListEmailWasVerified $event)
    {
        $email = $event->email;

        // FIXME: find then update or create
        MailingList::query()
                ->updateOrCreate(
                    [
                        MailingList::EMAIL_COLUMN       => $email,
                    ],
                    [
                        MailingList::IS_ACTIVE_COLUMN   => 1,
                    ]
                );
    }
}