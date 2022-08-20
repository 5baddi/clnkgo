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
use BADDIServices\ClnkGO\Models\Marketing\MailingList;

class TrackMailingList implements ShouldQueue
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
        public string $email
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
            MailingList::query()
                ->updateOrCreate(
                    [
                        MailingList::EMAIL_COLUMN       => $this->email,
                    ],
                    [
                        MailingList::IS_ACTIVE_COLUMN   => 1,
                        MailingList::SENT_AT_COLUMN     => Carbon::now(),
                    ]
                );
        } catch (Throwable $e) {
            AppLogger::error(
                $e,
                'marketing:track-mailing-list',
                func_get_args()
            );
        }
    }
}