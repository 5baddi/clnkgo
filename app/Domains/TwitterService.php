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
use BADDIServices\SourceeApp\Exceptions\Shopify\LoadConfigurationsFailed;

class TwitterService extends Service
{
    /** @var int */
    const MAX_RESULTS_PER_RESPONSE = 100;

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
    public function fetchTweetsByHashtags(string $hashtag): array
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
                        'max_results'   => self::MAX_RESULTS_PER_RESPONSE
                    ]
                ]
            );

            $data = json_decode($response->getBody(), true);
            if (! isset($data['data'])) {
                throw new Exception();
            }

            return $this->parseTweets($data['data']);
        } catch (Exception | ClientException | RequestException $e) {
            AppLogger::error($e, 'twitter:fetch-by-hashtags');

            throw new FetchByHashtagFailed();
        }
    }

    public function parseTweets(array $tweets = []): array
    {
        $parsedTweets = collect($tweets)
            ->map(function ($tweet) {
                $tweet['url'] = $this->getTweetUrl($tweet['author_id'], $tweet['id']);

                return $tweet;
            });

        return $parsedTweets->toArray();
    }

    private function getTweetUrl(string $authorId, string $tweetId): string
    {
        $url = (string)Str::replace("{authorId}", $authorId, self::TWEET_URL);
        $url = (string)Str::replace("{tweetId}", $tweetId, $url);

        return $url;
    }
}