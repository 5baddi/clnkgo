<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Listeners\Marketing;

use Illuminate\Contracts\Queue\ShouldQueue;
use BADDIServices\ClnkGO\Models\Marketing\MailingList;
use BADDIServices\ClnkGO\Services\CPALeadTrackingService;
use BADDIServices\ClnkGO\Models\Marketing\CPALeadTracking;
use BADDIServices\ClnkGO\Events\Marketing\CPALeadOfferMailWasSent;

class CPALeadOfferMailWasSentFired implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    public function __construct(
        private CPALeadTrackingService $CPALeadTrackingService
    ) {}

    public function handle(CPALeadOfferMailWasSent $event)
    {
        $email = $event->email;
        $sentAt = $event->sentAt;

        // FIXME: find then update or create
        $this->CPALeadTrackingService
            ->save([
                CPALeadTracking::EMAIL_COLUMN       => $email,
                CPALeadTracking::SENT_AT_COLUMN     => $sentAt,
            ]);

        // FIXME: use service
        MailingList::query()
            ->updateOrCreate(
                [
                    MailingList::EMAIL_COLUMN       => $email,
                ],
                [
                    MailingList::SENT_AT_COLUMN     => $sentAt,
                ]
            );
    }
}