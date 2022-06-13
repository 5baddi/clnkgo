<?php

namespace App\Console\Commands\Twitter;

use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use BADDIServices\SourceeApp\App;
use BADDIServices\SourceeApp\AppLogger;
use BADDIServices\SourceeApp\Models\Tweet;
use BADDIServices\SourceeApp\Models\AppSetting;
use BADDIServices\SourceeApp\Models\TwitterUser;
use BADDIServices\SourceeApp\Models\TwitterMedia;
use BADDIServices\SourceeApp\Domains\TwitterService;
use BADDIServices\SourceeApp\Services\AppSettingService;

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
        private AppSettingService $appSettingService,
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

                $this->saveTweets($hashtag, $tweets->toArray());

                sleep(3);
            });
        } catch (Throwable $e) {
            AppLogger::error($e, 'command:twitter:latest-tweets', ['execution_time' => (microtime(true) - $startTime)]);
            $this->error(sprintf("Error while fetching latest tweets: %s", $e->getMessage()));

            return;
        }

        $this->info("Done fetching latest tweets");
    }

    private function saveTweets(string $hashtag, array $tweets = []): void
    {
        collect($tweets['data'])
            ->map(function ($tweet) use ($hashtag, $tweets) {
                $dueAt = extractDate($tweet['text']);

                preg_match('/(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/im', $tweet['text'] ?? '', $emailMatches);

                if (isset($tweet['attachments'], $tweet['attachments']['media_keys'])) {
                    collect($tweet['attachments']['media_keys'])
                        ->each(function ($key) use ($tweets, $tweet) {
                            collect(isset($tweets['includes']['media']) ? $tweets['includes']['media'] : [])
                                ->each(function ($media) use($key, $tweet) {
                                    if (! isset($media['media_key']) || $media['media_key'] !== $key) {
                                        return true;
                                    }

                                    $this->twitterMediaService->save(
                                        [
                                            TwitterMedia::TWEET_ID_COLUMN           => $tweet['id'],
                                            TwitterMedia::ID_COLUMN                 => $media['media_key'],
                                            TwitterMedia::TYPE_COLUMN               => $media['type'],
                                            TwitterMedia::URL_COLUMN                => $media['url'] ?? null,
                                            TwitterMedia::PREVIEW_IMAGE_URL_COLUMN  => $media['preview_image_url'] ?? null,
                                            TwitterMedia::ALT_TEXT_COLUMN           => $media['alt_text'] ?? null,
                                            TwitterMedia::HEIGHT_COLUMN             => $media['height'] ?? null,
                                            TwitterMedia::WIDTH_COLUMN              => $media['width'] ?? null,
                                            TwitterMedia::DURATION_MS_COLUMN        => $media['duration_ms'] ?? null,
                                            TwitterMedia::PUBLIC_METRICS_COLUMN     => json_encode($media['public_metrics'] ?? null),
                                        ]
                                    );
                                });

                            
                        });
                }

                return $this->tweetService->save(
                    $hashtag,
                    [
                        Tweet::ID_COLUMN                    => $tweet['id'],
                        Tweet::URL_COLUMN                   => $this->twitterService->getTweetUrl($tweet['author_id'], $tweet['id']),
                        Tweet::PUBLISHED_AT_COLUMN          => Carbon::parse($tweet['created_at']),
                        Tweet::SOURCE_COLUMN                => $tweet['source'] ?? null,
                        Tweet::AUTHOR_ID_COLUMN             => $tweet['author_id'],
                        Tweet::TEXT_COLUMN                  => $tweet['text'],
                        Tweet::LANG_COLUMN                  => $tweet['lang'] ?? null,
                        Tweet::DUE_AT_COLUMN                => ! is_null($dueAt) ? Carbon::parse($dueAt) : null,
                        Tweet::EMAIL_COLUMN                 => ! empty($emailMatches[0]) ? strtolower($emailMatches[0]) : null,
                        Tweet::POSSIBLY_SENSITIVE_COLUMN    => $tweet['possibly_sensitive'] ?? false,
                        Tweet::IN_REPLY_TO_USER_ID_COLUMN   => $tweet['in_reply_to_user_id'] ?? null,
                        Tweet::REFERENCED_TWEETS_COLUMN     => json_encode($tweet['referenced_tweets'] ?? null),
                        Tweet::ATTACHMENTS_COLUMN           => json_encode($tweet['attachments'] ?? null),
                        Tweet::CONTEXT_ANNOTATIONS_COLUMN   => json_encode($tweet['context_annotations'] ?? null),
                        Tweet::PUBLIC_METRICS_COLUMN        => json_encode($tweet['public_metrics'] ?? null),
                        Tweet::GEO_COLUMN                   => json_encode($tweet['geo'] ?? null),
                        Tweet::ENTITIES_COLUMN              => json_encode($tweet['entities'] ?? null),
                        Tweet::WITHHELD_COLUMN              => json_encode($tweet['withheld'] ?? null),
                    ]
                );
            });

        collect(isset($tweets['includes']['users']) ? $tweets['includes']['users'] : [])
            ->each(function ($user) {
                $website = extractWebsite($user['description'] ?? '');
                preg_match('/(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/im', $user['description'] ?? '', $emailMatches);
                
                if (! isset($emailMatches[0]) && isset($user['location']) && filter_var($user['location'], FILTER_VALIDATE_EMAIL)) {
                    $emailMatches[0] = $user['location'];
                    $user['location'] = null;
                }

                if (is_null($website) && isset($emailMatches[0])) {
                    $website = extractWebsite($emailMatches[0] ?? '');
                }

                $this->twitterUserService->save(
                    [
                        TwitterUser::ID_COLUMN                    => $user['id'],
                        TwitterUser::USERNAME_COLUMN              => $user['username'],
                        TwitterUser::EMAIL_COLUMN                 => ! empty($emailMatches[0]) ? strtolower($emailMatches[0]) : null,
                        TwitterUser::WEBSITE_COLUMN               => $website,
                        TwitterUser::NAME_COLUMN                  => $user['name'] ?? null,
                        TwitterUser::VERIFIED_COLUMN              => $user['verified'] ?? false,
                        TwitterUser::PROTECTED_COLUMN             => $user['protected'] ?? false,
                        TwitterUser::PROFILE_IMAGE_URL_COLUMN     => $user['profile_image_url'] ? (string)Str::replace('_normal', '', $user['profile_image_url']) : null,
                        TwitterUser::PROFILE_BANNER_URL_COLUMN    => $user['profile_banner_url'] ?? null,
                        TwitterUser::DESCRIPTION_COLUMN           => $user['description'] ?? null,
                        TwitterUser::PINNED_TWEET_ID_COLUMN       => $user['pinned_tweet_id'] ?? null,
                        TwitterUser::LOCATION_COLUMN              => $user['location'] ?? null,
                        TwitterUser::URL_COLUMN                   => (is_string($user['url']) && strlen($user['url']) !== 0)  ? $user['url'] : $this->twitterService->getUserUrl($user['username']),
                        TwitterUser::REGISTERED_AT_COLUMN         => Carbon::parse($user['created_at'] ?? now()),
                        TwitterUser::ENTITIES_COLUMN              => json_encode($user['entities'] ?? null),
                        TwitterUser::PUBLIC_METRICS_COLUMN        => json_encode($user['public_metrics'] ?? null),
                        TwitterUser::WITHHELD_COLUMN              => json_encode($user['withheld'] ?? null),
                    ]
                );
            });

        if (! empty($tweets['meta']['next_token'])) {
            $tweets = $this->twitterService->fetchTweetsByHashtags($hashtag, null, $tweets['meta']['next_token']);
            
            $this->saveTweets($hashtag, $tweets->toArray());
        }
    }
}
