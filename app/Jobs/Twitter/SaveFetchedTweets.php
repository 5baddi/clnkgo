<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Jobs\Twitter;

use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use BADDIServices\ClnkGO\AppLogger;
use Illuminate\Queue\InteractsWithQueue;
use BADDIServices\ClnkGO\Models\Tweet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use BADDIServices\ClnkGO\Models\TwitterUser;
use BADDIServices\ClnkGO\Models\TwitterMedia;
use BADDIServices\ClnkGO\Services\TweetService;
use BADDIServices\ClnkGO\Domains\TwitterService;
use BADDIServices\ClnkGO\Helpers\EmojiParser;
use BADDIServices\ClnkGO\Services\TwitterUserService;
use BADDIServices\ClnkGO\Services\TwitterMediaService;

class SaveFetchedTweets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private TwitterService $twitterService;

    private TweetService $tweetService;

    private TwitterUserService $twitterUserService;

    private TwitterMediaService $twitterMediaService;

    private EmojiParser $emojiParser;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public string $hashtag,
        public array $tweets = []
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        TwitterService $twitterService,
        TweetService $tweetService,
        TwitterUserService $twitterUserService,
        TwitterMediaService $twitterMediaService,
        EmojiParser $emojiParser,
    ) {
        if (count($this->tweets) === 0) {
            return;
        }

        try {
            $this->twitterService = $twitterService;
            $this->tweetService = $tweetService;
            $this->twitterUserService = $twitterUserService;
            $this->twitterMediaService = $twitterMediaService;
            $this->emojiParser = $emojiParser;

            $this->saveTweets($this->hashtag, $this->tweets);

            if (! empty($this->tweets['meta']['next_token'])) {
                $tweets = $this->twitterService->fetchTweetsByHashtags($this->hashtag, null, $this->tweets['meta']['next_token']);

                self::dispatch($this->hashtag, $tweets->toArray());
            }
        } catch (Throwable $e) {
            AppLogger::error(
                $e,
                'job:save:latest-tweets',
                ['hashtag' => $this->hashtag, 'tweets' => $this->tweets]
            );
        }
    }

    private function saveTweets(string $hashtag, array $tweets = []): void
    {
        collect($tweets['data'])
            ->map(function ($tweet) use ($hashtag, $tweets) {
                $dueAt = extractDate($tweet['text']);
                $dueAt = $this->emojiParser->replace($dueAt ?? null, '');

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

                $emailMatches[0] = $this->emojiParser->replace($emailMatches[0] ?? null, '');
                $website = $this->emojiParser->replace($website ?? null, '');

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
    }
}
