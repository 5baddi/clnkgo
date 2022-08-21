<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Jobs\Twitter;

use Throwable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use BADDIServices\ClnkGO\AppLogger;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use BADDIServices\ClnkGO\Jobs\Marketing\NewEmailForMailingList;

class SaveFetchedEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /** @var string */
    private $tweetEmail;
    
    /** @var string */
    private $userEmail
    
    ;/** @var string */
    private $userName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public array $tweets = []
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (count($this->tweets) === 0) {
            return;
        }

        try {
            DB::beginTransaction();

            collect($this->tweets['data'] ?? [])
                ->each(function ($tweet) {
                    $this->tweetEmail = extractEmail($tweet['text'] ?? '');
                });
            
            collect($this->tweets['includes']['users'] ?? [])
                ->each(function ($user) {
                    $this->userEmail = extractEmail($user['description'] ?? '');
                    if (empty($this->userEmail) && isset($user['location']) && filter_var($user['location'], FILTER_VALIDATE_EMAIL)) {
                        $this->userEmail = $user['location'];
                    }
                
                    $this->userName = $user['name'] ?? null;
                });

            if (! empty($this->tweetEmail) && filter_var($this->tweetEmail, FILTER_VALIDATE_EMAIL)) {
                NewEmailForMailingList::dispatch($this->tweetEmail, null);
            }

            if (! empty($this->tweetEmail) && ! empty($this->userEmail) && $this->tweetEmail !== $this->userEmail && filter_var($this->userEmail, FILTER_VALIDATE_EMAIL)) {
                NewEmailForMailingList::dispatch($this->userEmail, $this->userName);
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            AppLogger::error(
                $e,
                'job:save-emails-tweets',
                func_get_args()
            );
        }
    }
}
