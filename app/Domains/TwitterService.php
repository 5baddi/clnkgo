<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Domains;

use BADDIServices\SourceeApp\App;
use Exception;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use BADDIServices\SourceeApp\AppLogger;
use GuzzleHttp\Exception\ClientException;
use BADDIServices\SourceeApp\Models\Tweet;
use GuzzleHttp\Exception\RequestException;
use BADDIServices\SourceeApp\Services\Service;
use BADDIServices\SourceeApp\Models\TwitterUser;
use BADDIServices\SourceeApp\Models\TwitterMedia;
use BADDIServices\SourceeApp\Services\TweetService;
use BADDIServices\SourceeApp\Services\TwitterUserService;
use BADDIServices\SourceeApp\Services\TwitterMediaService;
use BADDIServices\SourceeApp\Exceptions\Twitter\FetchByHashtagFailed;

class TwitterService extends Service
{
    /** @var int */
    const MAX_RESULTS_PER_RESPONSE = 10;

    /** @var string */
    const BASE_URL = "https://api.twitter.com/2/";
    const RECENT_SEARCH_ENDPOINT = "tweets/search/recent";
    const TWEET_URL = "https://twitter.com/{authorId}/status/{tweetId}";
    const USER_URL = "https://twitter.com/{username}";
    const DM_URL = "https://twitter.com/messages/compose?recipient_id={userId}&text={text}";

    /** @var Client */
    private $client;

    public function __construct(
        private TweetService $tweetService, 
        private TwitterUserService $twitterUserService, 
        private TwitterMediaService $twitterMediaService
    ) {
        parent::__construct();

        $this->client = new Client([
            'base_uri'      => self::BASE_URL,
            'debug'         => false,
            'http_errors'   => false,
        ]);
    }

    public function getDMLink(string $userId, string $text): string
    {
        $url = (string)Str::replace("{userId}", $userId, self::DM_URL);
        $url = (string)Str::replace("{text}", $text, $url);

        return $url;
    }

    /**
     * @throws FetchByHashtagFailed
     */
    public function fetchTweetsByHashtags(string $hashtag, ?string $startTime = null, ?string $nextToken = null): Collection
    {
        if (! $this->featureService->isEnabled(App::FETCH_TWEETS_FEATURE)) {
            return collect();
        }

        try {
            if (strlen($hashtag) === 0 || $hashtag === "") {
                return collect();
            }

            $query = [
                'query'         => sprintf('#%s -is:retweet', $hashtag),
                'tweet.fields'  => 'source,author_id,created_at,geo,lang,public_metrics,referenced_tweets,withheld,in_reply_to_user_id,possibly_sensitive,entities,context_annotations,attachments',
                'user.fields'   => 'created_at,description,entities,location,pinned_tweet_id,profile_image_url,protected,public_metrics,url,verified,withheld',
                'media.fields'  => 'duration_ms,height,preview_image_url,public_metrics,width,alt_text,url',
                'max_results'   => self::MAX_RESULTS_PER_RESPONSE,
                'expansions'    => 'attachments.media_keys,author_id,geo.place_id,in_reply_to_user_id,referenced_tweets.id'
            ];

            if (! empty($startTime)) {
                $query['start_time'] = date(DATE_RFC3339, strtotime($startTime));
            }
            
            if (! empty($nextToken)) {
                $query['next_token'] = $nextToken;

                sleep(10);
            }

            $response = $this->client->request('GET', self::RECENT_SEARCH_ENDPOINT, 
                [
                    'headers'   => [
                        'Accept'        => 'application/json',
                        'Authorization' => sprintf('Bearer %s', config('twitter.bearer_token'))
                    ],
                    'query'     => $query
                ]
            );

            $data = json_decode($response->getBody(), true);
            dd($data);
            if (isset($data['data']) && isset($data['meta']['result_count']) && $data['meta']['result_count'] > 0) {
                return $this->saveTweets($hashtag, $data);
            }

            return collect();
        } catch (Exception | ClientException | RequestException $e) {
            AppLogger::error($e, 'twitter:fetch-by-hashtags');

            throw new FetchByHashtagFailed();
        }
    }

    public function saveTweets(string $hashtag, array $tweets = []): Collection
    {
        dd($tweets);
        $parsedTweets = collect($tweets['data'])
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
                        Tweet::URL_COLUMN                   => $this->getTweetUrl($tweet['author_id'], $tweet['id']),
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
                        TwitterUser::URL_COLUMN                   => (is_string($user['url']) && strlen($user['url']) !== 0)  ? $user['url'] : $this->getUserUrl($user['username']),
                        TwitterUser::REGISTERED_AT_COLUMN         => Carbon::parse($user['created_at'] ?? now()),
                        TwitterUser::ENTITIES_COLUMN              => json_encode($user['entities'] ?? null),
                        TwitterUser::PUBLIC_METRICS_COLUMN        => json_encode($user['public_metrics'] ?? null),
                        TwitterUser::WITHHELD_COLUMN              => json_encode($user['withheld'] ?? null),
                    ]
                );
            });

        if (! empty($tweets['meta']['next_token'])) {
            return $this->fetchTweetsByHashtags($hashtag, null, $tweets['meta']['next_token']);
        }
        
        return $parsedTweets;
    }

    private function getTweetUrl(string $authorId, string $tweetId): string
    {
        $url = (string)Str::replace("{authorId}", $authorId, self::TWEET_URL);
        $url = (string)Str::replace("{tweetId}", $tweetId, $url);

        return $url;
    }
    
    private function getUserUrl(string $username): string
    {
        $url = (string)Str::replace("{username}", $username, self::USER_URL);

        return $url;
    }
}