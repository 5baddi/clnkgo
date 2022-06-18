<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Domains;

use GuzzleHttp\Client;
use BADDIServices\ClnkGO\Services\Service;

class PayPalService extends Service
{
    const BASE_URL = "https://api-m.sandbox.paypal.com/";
    const SANDBOX_BASE_URL = "https://api-m.sandbox.paypal.com/";

    public function __construct(
        private Client $client
    ) {
        $this->client = new Client([
            'base_uri'      => app()->environment() !== 'production' ? self::SANDBOX_BASE_URL : self::BASE_URL,
            'debug'         => false,
            'http_errors'   => false,
        ]);
    }
}