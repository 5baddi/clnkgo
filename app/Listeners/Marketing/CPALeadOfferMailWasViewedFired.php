<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Listeners\Marketing;

use Illuminate\Contracts\Queue\ShouldQueue;
use BADDIServices\ClnkGO\Services\CPALeadTrackingService;
use BADDIServices\ClnkGO\Models\Marketing\CPALeadTracking;
use BADDIServices\ClnkGO\Events\Marketing\CPALeadOfferMailWasViewed;

class CPALeadOfferMailWasViewedFired implements ShouldQueue
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

    public function handle(CPALeadOfferMailWasViewed $event)
    {
        $email = $event->email;
        $campaignId = $event->campaignId;
        $sentAt = $event->sentAt;

        $this->CPALeadTrackingService
            ->updateOrCreate(
                [CPALeadTracking::EMAIL_COLUMN       => $email],
                [
                    CPALeadTracking::SENT_AT_COLUMN     => $sentAt,
                    CPALeadTracking::CAMPAIGN_ID_COLUMN => $campaignId,
                ]
            );
    }
}