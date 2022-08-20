<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Jobs\Marketing;

use Throwable;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use BADDIServices\ClnkGO\AppLogger;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use BADDIServices\ClnkGO\Models\Marketing\CPALeadTracking;

class TrackCPALead implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public string $email,
        public string $campaignId
    ) {}

    public function middleware()
    {
        return [(new WithoutOverlapping($this->email))->releaseAfter(600)];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // FIXME: use service
            CPALeadTracking::query()
                ->updateOrCreate(
                    [
                        CPALeadTracking::EMAIL_COLUMN       => $this->email,
                    ],
                    [
                        CPALeadTracking::CAMPAIGN_ID_COLUMN => $this->campaignId,
                        CPALeadTracking::SENT_AT_COLUMN     => Carbon::now(),
                    ]
                );
        } catch (Throwable $e) {
            AppLogger::error(
                $e,
                'marketing:track-cpa-lead',
                func_get_args()
            );
        }
    }
}