<?php

namespace App\Console\Commands\Twitter;

use Throwable;
use Illuminate\Console\Command;
use BADDIServices\SourceeApp\AppLogger;
use BADDIServices\SourceeApp\Domains\TwitterService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FetchLatestTweets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twitter:latest-tweets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch latest tweets by hashtags';

    /** @var TwitterService */
    private $twitterService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TwitterService $twitterService)
    {
        parent::__construct();

        $this->twitterService = $twitterService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Start fetching latest tweets");
        $startTime = microtime(true);

        try {
            $hashtags = collect(config('twitter.hashtags'), []);

            $hashtags->each(function ($hashtag) {
                $this->twitterService->fetchTweetsByHashtags($hashtag);

                sleep(3);
            });
        } catch (Throwable $e) {
            AppLogger::error($e, 'command:twitter:latest-tweets', ['execution_time' => (microtime(true) - $startTime)]);
            $this->error(sprintf("Error while fetching latest tweets: %s", $e->getMessage()));

            return;
        }

        $this->info("Done fetching latest tweets");
    }

    private function fetchUsersKeywords(): Collection
    {
        $keywrods = collect();
        $usersKeywords = DB::table('users')->pluck('keywords');

        $usersKeywords = $usersKeywords->filter(function ($value) {
            return $value !== null || $value !== "" || strlen($value) > 0;
        });

        $usersKeywords->each(function ($value) use(&$keywrods) {
            if ($value === null || $value === "" || strlen($value) === 0) {
                return true;
            }

            $keywrods = $keywrods->merge(explode(',', trim($value)));
        });

        return $keywrods->unique();
    }
}
