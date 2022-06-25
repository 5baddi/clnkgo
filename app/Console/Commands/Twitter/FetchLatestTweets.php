<?php

namespace App\Console\Commands\Twitter;

use Throwable;
use BADDIServices\ClnkGO\App;
use Illuminate\Console\Command;
use BADDIServices\ClnkGO\AppLogger;
use BADDIServices\ClnkGO\Models\AppSetting;
use BADDIServices\ClnkGO\Services\TweetService;
use BADDIServices\ClnkGO\Domains\TwitterService;
use BADDIServices\ClnkGO\Services\AppSettingService;
use BADDIServices\ClnkGO\Services\TwitterUserService;
use BADDIServices\ClnkGO\Services\TwitterMediaService;
use BADDIServices\ClnkGO\Jobs\Twitter\SaveFetchedTweets;
use Illuminate\Support\Collection;

class FetchLatestTweets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twitter:latest-tweets {--start-time=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch latest tweets by hashtags';

    /** @var Collection */
    private $tweets;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private TwitterService $twitterService,
        private TweetService $tweetService,
        private TwitterUserService $twitterUserService, 
        private TwitterMediaService $twitterMediaService,
        private AppSettingService $appSettingService
    ) {
        parent::__construct();

        $this->tweets = Collection::make();
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
        $startTimeOption = ! is_null($this->option('start-time')) ? $this->option('start-time') : '-15 minutes';

        try {
            $hashtags = $this->appSettingService->get(AppSetting::MAIN_HASHTAGS_KEY, App::DEFAULT_MAIN_HASHTAGS);

            collect($hashtags ?? [])
                ->each(function ($hashtag) use ($startTimeOption) {
                    $this->fetchTweets($hashtag, $startTimeOption);

                    $this->tweets
                        ->each(function (Collection $tweets) use ($hashtag) {
                            SaveFetchedTweets::dispatch($hashtag, $tweets->toArray())
                                ->onQueue('tweets')
                                ->delay(15);
                        });
                });
        } catch (Throwable $e) {
            AppLogger::error($e, 'command:twitter:latest-tweets', ['execution_time' => (microtime(true) - $startTime)]);
            $this->error(sprintf("Error while fetching latest tweets: %s", $e->getMessage()));

            return;
        }

        $this->info("Done fetching latest tweets");
    }

    private function fetchTweets(string $hashtag, string $startTimeOption, ?string $nextToken = null): mixed
    {
        $tweets = $this->twitterService->fetchTweetsByHashtags($hashtag, $startTimeOption, $nextToken);

        $this->tweets->add($tweets);

        if (! empty($tweets['meta']['next_token'])) {
            return $this->fetchTweets($hashtag, $startTimeOption, $tweets['meta']['next_token']);
        }
    }
}
