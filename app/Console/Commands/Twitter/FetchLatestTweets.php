<?php

namespace App\Console\Commands\Twitter;

use Throwable;
use Illuminate\Console\Command;
use BADDIServices\SourceeApp\AppLogger;
use BADDIServices\SourceeApp\Domains\TwitterService;
use BADDIServices\SourceeApp\Services\TweetService;
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

    /** @var TweetService */
    private $tweetService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TwitterService $twitterService, TweetService $tweetService)
    {
        parent::__construct();

        $this->twitterService = $twitterService;
        $this->tweetService = $tweetService;
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
            $keywrods = $this->fetchUsersKeywords();

            $keywrods->map(function ($keyword) {
                $tweets = $this->twitterService->fetchTweetsByHashtags($keyword);

                $tweets->each(function ($tweet) use ($keyword) {
                    $this->tweetService->save($keyword, $tweet);
                });
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
            return $value !== null;
        });

        $usersKeywords->map(function ($value) use(&$keywrods) {
            $keywrods = $keywrods->merge(explode(',', trim($value)));
        });

        return $keywrods->unique();
    }
}
