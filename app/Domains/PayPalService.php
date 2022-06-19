<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Domains;

use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use BADDIServices\ClnkGO\AppLogger;
use BADDIServices\ClnkGO\Models\Subscription;
use GuzzleHttp\Exception\ClientException;
use BADDIServices\ClnkGO\Services\Service;
use BADDIServices\ClnkGO\Services\SubscriptionService;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Response;

class PayPalService extends Service
{
    const PRODUCATION_URL = "https://api-m.paypal.com/";
    const SANDBOX_URL = "https://api-m.sandbox.paypal.com/";
    const AUTHENTICATION_ENDPOINT = "v1/oauth2/token";
    const VERIFY_SIGNATURE_ENDPOINT = "v1/notifications/verify-webhook-signature";

    public const AUTH_ALGO_HEADER = 'PAYPAL-AUTH-ALGO';
    public const CERT_URL_HEADER = 'PAYPAL-CERT-URL';
    public const TRANSMISSION_ID_HEADER = 'PAYPAL-TRANSMISSION-ID';
    public const TRANSMISSION_SIG_HEADER = 'PAYPAL-TRANSMISSION-SIG';
    public const TRANSMISSION_TIME_HEADER = 'PAYPAL-TRANSMISSION-TIME';
    public const AUTH_VERSION_HEADER = 'PAYPAL-AUTH-VERSION';

    public const SUBSCRIPTION_CANCELLED_EVENT = 'BILLING.SUBSCRIPTION.CANCELLED';

    public const HEADERS = [
        self::AUTH_ALGO_HEADER,
        self::CERT_URL_HEADER,
        self::TRANSMISSION_ID_HEADER,
        self::TRANSMISSION_SIG_HEADER,
        self::TRANSMISSION_TIME_HEADER,
        self::AUTH_VERSION_HEADER,
    ];

    public const EVENTS = [
        self::SUBSCRIPTION_CANCELLED_EVENT,
    ];

    public function __construct(
        private SubscriptionService $subscriptionService
    ) {
        $this->client = new Client([
            'base_uri'      => app()->environment() !== 'production' ? self::SANDBOX_URL : self::PRODUCATION_URL,
            'debug'         => false,
            'http_errors'   => false,
        ]);
    }

    public function authenticate(): ?string
    {
        $auth = [
            config('paypal.client_id'),
            config('paypal.secret_key'),
        ];

        $body = [
            'grant_type'    => 'client_credentials'
        ];

        $response = $this->client
            ->request(
                'POST',
                self::AUTHENTICATION_ENDPOINT, 
                [
                    'headers'           => [
                        'Accept'        => 'application/json',
                    ],
                    'auth'              => $auth,
                    'body'              => json_encode($body)
                ]
            );

        $data = json_decode($response->getBody(), true);
        if ($response->getStatusCode() === Response::HTTP_OK && isset($data['access_token'])) {
            return $data['access_token'];
        }

        return null;
    }

    public function verifySignature(array $headers, string $eventType, string $webhookId): bool
    {
        try {
            $accessToken = $this->authenticate();

            $body = [
                'auth_algo'             => $headers[self::AUTH_ALGO_HEADER], 
                'cert_url'              => $headers[self::CERT_URL_HEADER], 
                'transmission_id'       => $headers[self::TRANSMISSION_ID_HEADER], 
                'transmission_sig'      => $headers[self::TRANSMISSION_SIG_HEADER], 
                'transmission_time'     => $headers[self::TRANSMISSION_TIME_HEADER],
                'event_type'            => $eventType,
                'webhook_id'            => $webhookId,
            ];

            $response = $this->client
                ->request(
                    'POST',
                    self::VERIFY_SIGNATURE_ENDPOINT, 
                    [
                        'headers'           => [
                            'Accept'        => 'application/json',
                            'Authorization' => sprintf('Bearer %s', $accessToken)
                        ],
                        'body'              => json_encode($body)
                    ]
                );

            $data = json_decode($response->getBody(), true);
            if ($response->getStatusCode() === Response::HTTP_OK && isset($data['verification_status']) && $data['verification_status'] === 'SUCCESS') {
                return true;
            }
        } catch (Exception | ClientException | RequestException $e) {
            AppLogger::error(
                $e,
                'paypal:verify-signature',
                compact($headers, $eventType, $webhookId)
            );
        }

        return false;
    }

    public function handleCancelledSubscription(array $resource): void
    {
        $subscription = $this->subscriptionService->findByChargeId($resource['id']);
        if (! $subscription instanceof Subscription || ! $subscription->user instanceof User) {
            return;
        }

        $this->subscriptionService->cancelSubscription($subscription->user, $subscription);
    }
}