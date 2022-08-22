<?php

namespace App\Console\Commands\Twitter;

use Throwable;
use Carbon\Carbon;
use BADDIServices\ClnkGO\App;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use BADDIServices\ClnkGO\AppLogger;
use BADDIServices\ClnkGO\Models\TwitterUser;
use BADDIServices\ClnkGO\Domains\TwitterService;
use BADDIServices\ClnkGO\Services\TwitterUserService;
use BADDIServices\ClnkGO\Models\Marketing\MailingList;

class FetchUserProfileByEmail extends Command
{
    /** @var int */
    const CHUNK_SIZE = 15;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twitter:fetch-user-profile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch user profile by email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private TwitterService $twitterService,
        private TwitterUserService $twitterUserService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Start fetching user profile by email");
        $startTime = microtime(true);

        try {
            $targetedEmails = MailingList::query()
                ->select([MailingList::EMAIL_COLUMN])
                // ->whereDate(MailingList::CREATED_AT, ">=", Carbon::now()->subDay())
                // ->orWhere(MailingList::IS_UNSUBSCRIBED_COLUMN, 1)
                ->where(MailingList::IS_UNSUBSCRIBED_COLUMN, 0)
                ->get();

            $targetedEmails->chunk(self::CHUNK_SIZE)
                ->each(function (Collection $emails) {
                    $emails->each(function (MailingList $mailingList) {
                        dd($this->twitterService->fetchTweetsByTerm($mailingList->getEmail()));
                    });
                });
        } catch (Throwable $e) {
            AppLogger::error($e, 'command:twitter:latest-tweets', ['execution_time' => (microtime(true) - $startTime)]);
            $this->error(sprintf("Error while fetching latest tweets: %s", $e->getMessage()));

            return;
        }

        $this->info("Done fetching user profile by email");
    }
}