<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Jobs\Twitter;

use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use BADDIServices\ClnkGO\AppLogger;
use BADDIServices\ClnkGO\Models\Tweet;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use BADDIServices\ClnkGO\Domains\TwitterService;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use BADDIServices\ClnkGO\Jobs\Twitter\SaveTweetUser;
use BADDIServices\ClnkGO\Jobs\Twitter\SaveTweetMedia;

class SaveFetchedTweets implements ShouldQueue
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
        public string $hashtag,
        public array $tweets = []
    ) {}

    public function middleware()
    {
        return [(new WithoutOverlapping($this->hashtag))->releaseAfter(30)];
    }

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
            /** @var TwitterService */
            $twitterService = app(TwitterService::class);

            DB::beginTransaction();

            $this->saveTweets($this->hashtag, $this->tweets);

            DB::commit();

            if (! empty($this->tweets['meta']['next_token'])) {
                $tweets = $twitterService->fetchTweetsByHashtags($this->hashtag, null, $this->tweets['meta']['next_token']);

                self::dispatch($this->hashtag, $tweets->toArray())
                    ->onQueue('tweets');
            }
        } catch (Throwable $e) {
            DB::rollBack();

            AppLogger::error(
                $e,
                'job:save-latest-tweets',
                func_get_args()
            );
        }
    }

    private function saveTweets(string $hashtag, array $tweets = []): void
    {
        collect($tweets['data'])
            ->map(function ($tweet) use ($hashtag, $tweets) {
                if (Arr::has($tweet, Tweet::ID_COLUMN)) {
                    SaveTweet::dispatch($hashtag, $tweet)
                        ->onQueue('tweets');
                }

                if (isset($tweet['attachments'], $tweet['attachments']['media_keys'])) {
                    collect($tweet['attachments']['media_keys'])
                        ->each(function ($key) use ($tweets, $tweet) {
                            collect(isset($tweets['includes']['media']) ? $tweets['includes']['media'] : [])
                                ->each(function ($media) use($key, $tweet) {
                                    if (! isset($media['media_key']) || $media['media_key'] !== $key) {
                                        return true;
                                    }

                                    SaveTweetMedia::dispatch($tweet[Tweet::ID_COLUMN], $media)
                                        ->onQueue('tweets');
                                });

                            
                        });
                }
            });

        collect(isset($tweets['includes']['users']) ? $tweets['includes']['users'] : [])
            ->each(function ($user) {
                if (! Arr::has($user, 'id')) {
                    return true;
                }

                SaveTweetUser::dispatch($user)
                    ->onQueue('tweets');
            });
    }
}
