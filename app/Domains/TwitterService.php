<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Domains;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use BADDIServices\ClnkGO\App;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use BADDIServices\ClnkGO\AppLogger;
use GuzzleHttp\Exception\ClientException;
use BADDIServices\ClnkGO\Services\Service;
use GuzzleHttp\Exception\RequestException;
use BADDIServices\ClnkGO\Exceptions\Twitter\FetchByHashtagFailed;
use Illuminate\Support\Arr;

class TwitterService extends Service
{
    /** @var int */
    const MAX_RESULTS_PER_RESPONSE = 100;
    const CLNKGO_USER_ID = 1366518168160731145;
    const OWNER_USER_ID = 1421214978;

    /** @var string */
    const BASE_API_V1_URL = "https://api.twitter.com/1.1/";
    const BASE_API_V2_URL = "https://api.twitter.com/2/";
    const RECENT_SEARCH_ENDPOINT = "tweets/search/recent";
    const DIRECT_MESSAGE_ENDPOINT = "direct_messages/events/new.json";
    const USER_SHOW_ENDPOINT = "users/show.json";

    const TWEET_URL = "https://twitter.com/{authorId}/status/{tweetId}";
    const USER_URL = "https://twitter.com/{username}";
    const DM_URL = "https://twitter.com/messages/compose?recipient_id={userId}&text={text}";

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
                'tweet.fields'  => 'source,author_id,created_at,geo,lang,public_metrics,referenced_tweets,withheld,in_reply_to_user_id,possibly_sensitive,entities,context_annotations,attachments',
                'user.fields'   => 'created_at,description,entities,location,pinned_tweet_id,profile_image_url,protected,public_metrics,url,verified,withheld',
                'media.fields'  => 'duration_ms,height,preview_image_url,public_metrics,width,alt_text,url',
                'max_results'   => self::MAX_RESULTS_PER_RESPONSE,
                'expansions'    => 'attachments.media_keys,author_id,geo.place_id,in_reply_to_user_id,referenced_tweets.id'
            ];

            if (is_null($nextToken)) {
                $query['query'] = sprintf('#%s -is:retweet', $hashtag);
            }

            if (! empty($startTime)) {
                $query['start_time'] = date(DATE_RFC3339, strtotime($startTime));
            }
            
            if (! empty($nextToken)) {
                $query['next_token'] = $nextToken;

                sleep(10);
            }

            $response = $this->getClient()
                ->request(
                    'GET',
                    self::RECENT_SEARCH_ENDPOINT, 
                    [
                        'headers'   => [
                            'Accept'        => 'application/json',
                            'Authorization' => sprintf('Bearer %s', config('twitter.bearer_token'))
                        ],
                        'query'     => $query
                    ]
                );

            $data = json_decode($response->getBody(), true);
            if ($response->getStatusCode() === Response::HTTP_OK && isset($data['data']) && isset($data['meta']['result_count']) && $data['meta']['result_count'] > 0) {
                return collect($data);
            }

            return collect();
        } catch (Exception | ClientException | RequestException $e) {
            AppLogger::error($e, 'twitter:fetch-by-hashtags');

            throw new FetchByHashtagFailed();
        }
    }

    public function fetchTweetsByTerm(string $term, ?string $startTime = null, ?string $nextToken = null): Collection
    {
        if (! $this->featureService->isEnabled(App::FETCH_TWEETS_FEATURE)) {
            return collect();
        }

        try {
            if (strlen($term) === 0 || $term === "") {
                return collect();
            }

            $query = [
                'tweet.fields'  => 'source,author_id,created_at,geo,lang,public_metrics,referenced_tweets,withheld,in_reply_to_user_id,possibly_sensitive,entities,context_annotations,attachments',
                'user.fields'   => 'created_at,description,entities,location,pinned_tweet_id,profile_image_url,protected,public_metrics,url,verified,withheld',
                'media.fields'  => 'duration_ms,height,preview_image_url,public_metrics,width,alt_text,url',
                'max_results'   => self::MAX_RESULTS_PER_RESPONSE,
                'expansions'    => 'attachments.media_keys,author_id,geo.place_id,in_reply_to_user_id,referenced_tweets.id'
            ];

            if (is_null($nextToken)) {
                $email = explode('@', $term);
                $query['query'] = sprintf('"%s" -is:retweet', $email[0] ?? '');
            }

            // if (! empty($startTime)) {
            //     $query['start_time'] = date(DATE_RFC3339, strtotime($startTime));
            // }
            
            if (! empty($nextToken)) {
                $query['next_token'] = $nextToken;

                sleep(10);
            }

            $response = $this->getClient()
                ->request(
                    'GET',
                    self::RECENT_SEARCH_ENDPOINT, 
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
            if ($response->getStatusCode() === Response::HTTP_OK && isset($data['data']) && isset($data['meta']['result_count']) && $data['meta']['result_count'] > 0) {
                return collect($data);
            }
        } catch (Exception | ClientException | RequestException $e) {
            AppLogger::error($e, 'twitter:fetch-by-hashtags');
        }

        return collect();
    }

    public function fetchUserProfile(int $userId, string $userName): Collection
    {
        if (! $this->featureService->isEnabled(App::FETCH_TWEETS_FEATURE)) {
            return collect();
        }

        try {
            $query = [
                'user_id'       => $userId,
                'screen_name'   => $userName,
            ];

            $response = $this->getClient(1)
                ->request(
                    'GET',
                    self::USER_SHOW_ENDPOINT, 
                    [
                        'headers'   => [
                            'Accept'        => 'application/json',
                            'Authorization' => sprintf('Bearer %s', config('twitter.bearer_token'))
                        ],
                        'query'     => $query
                    ]
                );

            $data = json_decode($response->getBody(), true);
            if ($response->getStatusCode() === Response::HTTP_OK && ! Arr::has($data, 'errors')) {
                return collect($data);
            }
        } catch (Exception | ClientException | RequestException $e) {
            AppLogger::error($e, 'twitter:fetch-user-profile', func_get_args());
        }

        return collect();
    }

    public function sendDirectMessage(string $recipientId, string $message, ?string $senderId = null): void
    {
        // FIXME:
        return;
        try{
            $body = [
                'event' => [
                    'type'  => 'message_create',
                    'message_create'    => [
                        'target'        => [
                            'recipient_id'  => $recipientId
                        ],
                        'message_data'  => [
                            'text'      => $message
                        ]
                    ]
                ]
            ];

            if (! empty($senderId)) {
                $body = array_merge(
                    $body,
                    [
                        'event'                     => [
                            'message_create'        => [
                                'custom_profile_id' => $senderId
                            ]
                        ]
                    ]
                );
            }

            $response = $this->getClient(1)
                ->request(
                    'POST',
                    self::DIRECT_MESSAGE_ENDPOINT,
                    [
                        'headers'   => [
                            'Accept'        => 'application/json',
                            'Authorization' => sprintf('Bearer %s', config('twitter.bearer_token')) // FIXME: OAuth 2
                        ],
                        'body'      => json_encode($body)
                    ]
                );
            
            $data = json_decode($response->getBody(), true);
        } catch (Exception | ClientException | RequestException $e) {
            AppLogger::error($e, 'twitter:send-direct-message');

            throw new FetchByHashtagFailed();
        }
    }

    private function getClient(?int $version = 2): Client
    {
        $baseUri = (is_null($version) || $version === 2) ? self::BASE_API_V2_URL : self::BASE_API_V1_URL;

        return new Client([
            'base_uri'      => $baseUri,
            'debug'         => false,
            'http_errors'   => false,
        ]);
    }
}