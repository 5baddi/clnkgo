<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Domains;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use BADDIServices\SourceeApp\App;
use Illuminate\Support\Collection;
use BADDIServices\SourceeApp\AppLogger;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use BADDIServices\SourceeApp\Services\Service;
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

    public function __construct() 
    {
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

    public function getTweetUrl(string $authorId, string $tweetId): string
    {
        $url = (string)Str::replace("{authorId}", $authorId, self::TWEET_URL);
        $url = (string)Str::replace("{tweetId}", $tweetId, $url);

        return $url;
    }
    
    public function getUserUrl(string $username): string
    {
        $url = (string)Str::replace("{username}", $username, self::USER_URL);

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
            if (isset($data['data']) && isset($data['meta']['result_count']) && $data['meta']['result_count'] > 0) {
                return collect($data);
            }

            return collect();
        } catch (Exception | ClientException | RequestException $e) {
            AppLogger::error($e, 'twitter:fetch-by-hashtags');

            throw new FetchByHashtagFailed();
        }
    }

    public function sendDirectMessage(string $recipientId, string $senderId, string $message): void
    {

    }
}