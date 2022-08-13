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

class CPAleadService extends Service
{
    /** @var int */
    const USER_ID = 2125240;

    /** @var string */
    const BASE_API_URL = "https://cpalead.com/";
    const LIST_AVAILABLE_OFFERS_ENDPOINT = "dashboard/reports/campaign_json.php?id={userId}";

    const EMAIL_SUMIT_OFFER_TYPE = "email_submit";

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

    public function getListAvailableOffersLink(string $userId = self::USER_ID, string $offerType = self::EMAIL_SUMIT_OFFER_TYPE): string
    {
        $url = (string)Str::replace("{userId}", $userId, self::LIST_AVAILABLE_OFFERS_ENDPOINT);
        $url .= sprintf("&offer_type=%s&format=JSON", $offerType);

        return $url;
    }

    public function fetchCpaleadOffers(string $offerType = self::EMAIL_SUMIT_OFFER_TYPE): Collection
    {
        if (! $this->featureService->isEnabled(App::FETCH_CPALEAD_OFFERS_FEATURE)) {
            return collect();
        }

        try {
            if (strlen($offerType) === 0 || $offerType === "") {
                return collect();
            }

            $response = $this->client
                ->request(
                    'GET',
                    self::LIST_AVAILABLE_OFFERS_ENDPOINT, 
                    [
                        'headers'   => [
                            'Accept'        => 'application/json',
                        ],
                    ]
                );

            $data = json_decode($response->getBody(), true);
            dd($data);
            // if ($response->getStatusCode() === Response::HTTP_OK && isset($data['data']) && isset($data['meta']['result_count']) && $data['meta']['result_count'] > 0) {
            //     return collect($data);
            // }
        } catch (Exception | ClientException | RequestException $e) {
            AppLogger::error($e, 'twitter:fetch-by-hashtags');
        }

        return collect();
    }
}