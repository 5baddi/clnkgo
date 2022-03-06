<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Domains;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use BADDIServices\SourceeApp\AppLogger;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use BADDIServices\SourceeApp\Services\Service;
use BADDIServices\SourceeApp\Exceptions\Twitter\FetchByHashtagFailed;
use BADDIServices\SourceeApp\Models\Tweet;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TwitterService extends Service
{
    /** @var int */
    const MAX_RESULTS_PER_RESPONSE = 10;

    /** @var string */
    const BASE_URL = "https://api.twitter.com/2/";
    const RECENT_SEARCH_ENDPOINT = "tweets/search/recent";
    const TWEET_URL = "https://twitter.com/{authorId}/status/{tweetId}";

    /** @var Client */
    private $client;

    public function __construct()
    {
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
                        'query'         => $hashtag,
                        'start_time'    => date(DATE_RFC3339, strtotime('-15 minutes')),
                        'tweet.fields'  => 'id,text,source,author_id,created_at,geo,lang,public_metrics,referenced_tweets,withheld,in_reply_to_user_id,possibly_sensitive,entities,context_annotations,attachments',
                        'user.fields'   => 'id,name,username,created_at,description,entities,location,pinned_tweet_id,profile_image_url,protected,public_metrics,url,verified,withheld',
                        'media.fields'  => 'duration_ms,height,media_key,preview_image_url,public_metrics,type,width,alt_text',
                        'max_results'   => self::MAX_RESULTS_PER_RESPONSE,
                        'expansions'    => 'attachments.media_keys,author_id,geo.place_id,in_reply_to_user_id,referenced_tweets.id'
                    ]
                ]
            );

            $data = json_decode($response->getBody(), true);
            if (! isset($data['data'])) {
                throw new Exception();
            }

            return $this->parseTweets($data);
        } catch (Exception | ClientException | RequestException $e) {
            AppLogger::error($e, 'twitter:fetch-by-hashtags');

            throw new FetchByHashtagFailed();
        }
    }

    public function parseTweets(array $tweets = []): Collection
    {
        $parsedTweets = collect($tweets['data'])
            ->map(function ($tweet) {
                return [
                    Tweet::ID_COLUMN                => $tweet['id'],
                    Tweet::URL_COLUMN               => $this->getTweetUrl($tweet['author_id'], $tweet['id']),
                    Tweet::PUBLISHED_AT_COLUMN      => Carbon::parse($tweet['created_at']),
                    Tweet::SOURCE_COLUMN            => $tweet['source'] ?? null,
                    Tweet::AUTHOR_ID_COLUMN         => $tweet['author_id'],
                    Tweet::TEXT_COLUMN              => $tweet['text'],
                    Tweet::LANG_COLUMN              => $tweet['lang'] ?? null,
                    Tweet::PUBLIC_METRICS_COLUMN    => $tweet['public_metrics'] ?? null,
                    Tweet::ENTITIES_COLUMN          => $tweet['entities'] ?? null,
                ];
            });

        return $parsedTweets;
    }

    private function getTweetUrl(string $authorId, string $tweetId): string
    {
        $url = (string)Str::replace("{authorId}", $authorId, self::TWEET_URL);
        $url = (string)Str::replace("{tweetId}", $tweetId, $url);

        return $url;
    }
}