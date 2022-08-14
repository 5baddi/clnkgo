<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Listeners\Marketing;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Queue\ShouldQueue;
use BADDIServices\ClnkGO\Models\Marketing\CPALeadTracking;
use BADDIServices\ClnkGO\Services\CPALeadTrackingService;
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
        $data = $event->trackingData;

        if (! Arr::has(
            $data, 
            [
                CPALeadTracking::CAMPAIGN_ID_COLUMN,
                CPALeadTracking::EMAIL_COLUMN,
                CPALeadTracking::SENT_AT_COLUMN,
            ]
        )) {
            return;
        }

        // FIXME: find then update or create
        $this->CPALeadTrackingService
            ->save($data);
    }
}