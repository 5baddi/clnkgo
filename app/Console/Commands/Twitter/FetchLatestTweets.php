<?php

namespace App\Console\Commands\Twitter;

use Throwable;
use Illuminate\Console\Command;
use BADDIServices\SourceeApp\App;
use BADDIServices\SourceeApp\AppLogger;
use BADDIServices\SourceeApp\Models\AppSetting;
use BADDIServices\SourceeApp\Services\TweetService;
use BADDIServices\SourceeApp\Domains\TwitterService;
use BADDIServices\SourceeApp\Jobs\Twitter\SaveFetchedTweets;
use BADDIServices\SourceeApp\Services\AppSettingService;
use BADDIServices\SourceeApp\Services\TwitterUserService;
use BADDIServices\SourceeApp\Services\TwitterMediaService;

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
        $startTimeOption = $this->hasOption('start-time') ? $this->option('start-time') : '-15 minutes';

        try {
            $hashtags = $this->appSettingService->get(AppSetting::MAIN_HASHTAGS_KEY, App::DEFAULT_MAIN_HASHTAGS);

            collect($hashtags ?? [])->each(function ($hashtag) use ($startTimeOption) {
                $tweets = $this->twitterService->fetchTweetsByHashtags($hashtag, $startTimeOption);

                SaveFetchedTweets::dispatch($hashtag, $tweets->toArray());
            });
        } catch (Throwable $e) {
            AppLogger::error($e, 'command:twitter:latest-tweets', ['execution_time' => (microtime(true) - $startTime)]);
            $this->error(sprintf("Error while fetching latest tweets: %s", $e->getMessage()));

            return;
        }

        $this->info("Done fetching latest tweets");
    }
}
