<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Domains;

use Exception;
use GuzzleHttp\Client;
use BADDIServices\ClnkGO\App;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use BADDIServices\ClnkGO\AppLogger;
use GuzzleHttp\Exception\ClientException;
use BADDIServices\ClnkGO\Services\Service;
use GuzzleHttp\Exception\RequestException;

class NewsService extends Service
{
    /** @var int */
    const USER_ID = 2125240;

    /** @var string */
    const BASE_API_URL = "https://newsapi.org/v2/";
    const TOP_HEADLINES_ENDPOINT = "top-headlines";

    const FORTUNE_SOURCE = "fortune";
    const AUSTRALIAN_FINANCIAL_REVIEW_SOURCE = "australian-financial-review";

    const SUPPORTED_SOURCES = [
        self::FORTUNE_SOURCE,
        self::AUSTRALIAN_FINANCIAL_REVIEW_SOURCE,
    ];

    private Client $client;

    public function __construct()
    {
        parent::__construct();

        $this->client = new Client([
            'base_uri'      => self::BASE_API_URL,
            'debug'         => false,
            'http_errors'   => false,
        ]);
    }

    public function getTopHeadlinesLink(): string
    {
        return self::BASE_API_URL . self::TOP_HEADLINES_ENDPOINT;
    }

    public function getTopHeadlines(): Collection
    {
        if (! $this->featureService->isEnabled(App::FETCH_NEWS_FEATURE)) {
            return collect();
        }

        try {
            $response = $this->client
                ->request(
                    'GET',
                    $this->getTopHeadlinesLink(), 
                    [
                        'headers'   => [
                            'Accept'    => 'application/json',
                        ],
                        'query'     => [
                            'apiKey'    => config('baddi.news_api_key'),
                            'sources'   => implode(',', self::SUPPORTED_SOURCES),
                        ],
                    ]
                );

            $data = json_decode($response->getBody(), true);
            if ($response->getStatusCode() === Response::HTTP_OK && isset($data['articles'])) {
                return collect($data['articles']);
            }
        } catch (Exception | ClientException | RequestException $e) {
            AppLogger::error($e, 'news:fetch-top-headlines');
        }

        return collect();
    }
}