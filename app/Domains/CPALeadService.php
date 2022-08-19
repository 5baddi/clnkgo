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

class CPALeadService extends Service
{
    /** @var int */
    const USER_ID = 2125240;

    /** @var string */
    const BASE_API_URL = "https://cpalead.com/";
    const LIST_AVAILABLE_OFFERS_ENDPOINT = "dashboard/reports/campaign_json.php?id={userId}";

    const EMAIL_SUMIT_OFFER_TYPE = "email_submit";
    const APP_INSTALL_OFFER_TYPE = "app_install";
    const MOBILE_OFFER_TYPE = "mobile";

    const DESKTOP_USER_AGENT = "desktop device";
    const ANDROID_USER_AGENT = "Android phone";
    const IOS_USER_AGENT = "IOS phone";

    const SUPPORTED_OFFER_TYPES = [
        self::EMAIL_SUMIT_OFFER_TYPE,
        self::APP_INSTALL_OFFER_TYPE,
        self::MOBILE_OFFER_TYPE,
    ];

    const SUPPORTED_USER_AGENTS = [
        self::DESKTOP_USER_AGENT,
        self::ANDROID_USER_AGENT,
        self::IOS_USER_AGENT,
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

    public function getListAvailableOffersLink(string $userId = self::USER_ID): string
    {
        $url = (string)Str::replace("{userId}", $userId, self::LIST_AVAILABLE_OFFERS_ENDPOINT);
        $url .= "&format=JSON&offerwall_offers=false&dating=true";

        return $url;
    }

    public function fetchCPALeadOffers(): Collection
    {
        if (! $this->featureService->isEnabled(App::FETCH_CPALEAD_OFFERS_FEATURE)) {
            return collect();
        }

        try {
            $response = $this->client
                ->request(
                    'GET',
                    $this->getListAvailableOffersLink(self::USER_ID), 
                    [
                        'headers'   => [
                            'Accept'        => 'application/json',
                        ],
                    ]
                );

            $data = json_decode($response->getBody(), true);
            if ($response->getStatusCode() === Response::HTTP_OK && isset($data['offers'])) {
                return collect($data['offers']);
            }
        } catch (Exception | ClientException | RequestException $e) {
            AppLogger::error($e, 'twitter:fetch-by-hashtags');
        }

        return collect();
    }
    
    public function getCPALeadOffersByGeoAndUserAgent(string $ip, string $userAgent = self::DESKTOP_USER_AGENT): Collection
    {
        if (! $this->featureService->isEnabled(App::FETCH_CPALEAD_OFFERS_FEATURE)) {
            return collect();
        }

        if (! in_array($userAgent, self::SUPPORTED_USER_AGENTS)) {
            $userAgent = self::DESKTOP_USER_AGENT;
        }

        try {
            $endpoint = $this->getListAvailableOffersLink(self::USER_ID);
            $endpoint = sprintf('%s&geoip=%s&ua=%s', $endpoint, $ip, $userAgent);

            $response = $this->client
                ->request(
                    'GET',
                    $endpoint, 
                    [
                        'headers'   => [
                            'Accept'        => 'application/json',
                        ],
                    ]
                );

            $data = json_decode($response->getBody(), true);
            if ($response->getStatusCode() === Response::HTTP_OK && isset($data['offers'])) {
                return collect($data['offers']);
            }
        } catch (Exception | ClientException | RequestException $e) {
            AppLogger::error($e, 'twitter:fetch-by-hashtags');
        }

        return collect();
    }
}