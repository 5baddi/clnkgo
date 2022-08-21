<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Jobs\Marketing;

use Throwable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use BADDIServices\ClnkGO\AppLogger;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use BADDIServices\ClnkGO\Models\Marketing\MailingList;

class NewEmailForMailingList implements ShouldQueue
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
        public ?string $name = null
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
            DB::beginTransaction();

            // FIXME: use service
            MailingList::query()
            ->updateOrCreate(
                [
                    MailingList::EMAIL_COLUMN => strtolower($this->email),
                ],
                [
                    MailingList::NAME_COLUMN => $this->name,
                ]
            );

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            AppLogger::error(
                $e,
                'mailing-list:new-email',
                func_get_args()
            );
        }
    }
}