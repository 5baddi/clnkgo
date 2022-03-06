<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Domains;

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
use BADDIServices\SourceeApp\Services\TweetService;
use BADDIServices\SourceeApp\Exceptions\Twitter\FetchByHashtagFailed;
use BADDIServices\SourceeApp\Models\TwitterMedia;
use BADDIServices\SourceeApp\Models\TwitterUser;
use BADDIServices\SourceeApp\Services\TwitterMediaService;
use BADDIServices\SourceeApp\Services\TwitterUserService;

class TwitterService extends Service
{
    /** @var int */
    const MAX_RESULTS_PER_RESPONSE = 10;

    /** @var string */
    const BASE_URL = "https://api.twitter.com/2/";
    const RECENT_SEARCH_ENDPOINT = "tweets/search/recent";
    const TWEET_URL = "https://twitter.com/{authorId}/status/{tweetId}";
    const USER_URL = "https://twitter.com/{username}";

    /** @var Client */
    private $client;

    /** @var TweetService */
    private $tweetService;

    /** @var TwitterUserService */
    private $twitterUserService;

    /** @var TwitterMediaService */
    private $twitterMediaService;

    public function __construct(TweetService $tweetService, TwitterUserService $twitterUserService, TwitterMediaService $twitterMediaService)
    {
        $this->tweetService = $tweetService;
        $this->twitterUserService = $twitterUserService;
        $this->twitterMediaService = $twitterMediaService;
        
        $this->client = new Client([
            'base_uri'      => self::BASE_URL,
            'debug'         => false,
            'http_errors'   => false,
        ]);
    }

    /**
     * @throws FetchByHashtagFailed
     */
    public function fetchTweetsByHashtags(string $hashtag): Collection
    {
        try {
            $response = $this->client->request('GET', self::RECENT_SEARCH_ENDPOINT, 
                [
                    'headers'   => [
                        'Accept'        => 'application/json',
                        'Authorization' => sprintf('Bearer %s', config('twitter.bearer_token'))
                    ],
                    'query'     => [
                        'query'         => sprintf('#%s', $hashtag),
                        'start_time'    => date(DATE_RFC3339, strtotime('-15 minutes')),
                        'tweet.fields'  => 'id,text,source,author_id,created_at,geo,lang,public_metrics,referenced_tweets,withheld,in_reply_to_user_id,possibly_sensitive,entities,context_annotations,attachments',
                        'user.fields'   => 'id,name,username,created_at,description,entities,location,pinned_tweet_id,profile_image_url,protected,public_metrics,url,verified,withheld',
                        'media.fields'  => 'duration_ms,height,media_key,preview_image_url,public_metrics,type,width,alt_text,url',
                        'max_results'   => self::MAX_RESULTS_PER_RESPONSE,
                        'expansions'    => 'attachments.media_keys,author_id,geo.place_id,in_reply_to_user_id,referenced_tweets.id'
                    ]
                ]
            );

            $data = json_decode($response->getBody(), true);
            if (! isset($data['data'])) {
                throw new Exception();
            }

            return $this->saveTweets($hashtag, $data);
        } catch (Exception | ClientException | RequestException $e) {
            AppLogger::error($e, 'twitter:fetch-by-hashtags');

            throw new FetchByHashtagFailed();
        }
    }

    public function saveTweets(string $hashtag, array $tweets = []): Collection
    {
        $parsedTweets = collect($tweets['data'])
            ->map(function ($tweet) use ($hashtag) {
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
                $this->twitterUserService->save(
                    [
                        TwitterUser::ID_COLUMN                    => $user['id'],
                        TwitterUser::USERNAME_COLUMN              => $user['username'],
                        TwitterUser::NAME_COLUMN                  => $user['name'] ?? null,
                        TwitterUser::VERIFIED_COLUMN              => $user['verified'] ?? false,
                        TwitterUser::PROTECTED_COLUMN             => $user['protected'] ?? false,
                        TwitterUser::PROFILE_IMAGE_URL_COLUMN     => $user['profile_image_url'] ? (string)Str::replace('_normal', '', $user['profile_image_url']) : null,
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
            
        collect(isset($tweets['includes']['media']) ? $tweets['includes']['media'] : [])
            ->each(function ($media) {
                $this->twitterMediaService->save(
                    [
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