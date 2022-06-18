<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Jobs\Twitter;

use Throwable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use BADDIServices\ClnkGO\AppLogger;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use BADDIServices\ClnkGO\Domains\TwitterService;

class SendDirectMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public string $recipientId,
        public string $message,
        public ?string $senderId = null
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            /** @var TwitterService $twitterService */
            $twitterService = app(TwitterService::class);

            // FIXME:
            // $twitterService->sendDirectMessage($this->recipientId, $this->message, $this->senderId);
        } catch (Throwable $e) {
            AppLogger::error($e, 'twitter:send-direct-message-job', get_object_vars($this));
        }
    }
}