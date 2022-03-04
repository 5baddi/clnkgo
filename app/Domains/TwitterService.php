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
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use BADDIServices\SourceeApp\AppLogger;
use BADDIServices\SourceeApp\Models\Pack;
use GuzzleHttp\Exception\ClientException;
use BADDIServices\SourceeApp\Models\OAuth;
use BADDIServices\SourceeApp\Models\Store;
use GuzzleHttp\Exception\RequestException;
use BADDIServices\SourceeApp\Services\Service;
use Symfony\Component\HttpFoundation\Response;
use BADDIServices\SourceeApp\Exceptions\Shopify\OrderNotFound;
use BADDIServices\SourceeApp\Exceptions\Shopify\ProductNotFound;
use BADDIServices\SourceeApp\Exceptions\Shopify\CustomerNotFound;
use BADDIServices\SourceeApp\Exceptions\Shopify\AcceptPaymentFailed;
use BADDIServices\SourceeApp\Exceptions\Shopify\CreateDiscountFailed;
use BADDIServices\SourceeApp\Exceptions\Shopify\FetchResourcesFailed;
use BADDIServices\SourceeApp\Exceptions\Shopify\CreatePriceRuleFailed;
use BADDIServices\SourceeApp\Exceptions\Shopify\CancelSubscriptionFailed;
use BADDIServices\SourceeApp\Exceptions\Shopify\InvalidStoreURLException;
use BADDIServices\SourceeApp\Exceptions\Shopify\LoadConfigurationsFailed;
use BADDIServices\SourceeApp\Exceptions\Shopify\InvalidAccessTokenException;
use BADDIServices\SourceeApp\Exceptions\Shopify\IntegateAppLayoutToThemeFailed;
use BADDIServices\SourceeApp\Exceptions\Shopify\CreatePaymentConfirmationFailed;
use BADDIServices\SourceeApp\Exceptions\Shopify\InvalidRequestSignatureException;

class TwitterService extends Service
{
    /** @var int */
    const MAX_RESULTS_PER_RESPONSE = 100;

    /** @var string */
    const BASE_URL = "https://api.twitter.com/2/";
    const RECENT_SEARCH_ENDPOINT = "tweets/search/recent";

    const SCOPES = "read_orders,read_customers,read_products,read_checkouts,read_price_rules,write_price_rules,read_discounts,write_discounts,read_script_tags,write_script_tags";
    const STORE_ENDPOINT = "https://{store}.myshopify.com";
    const STORE_CONFIGS_ENDPOINT = "/admin/api/2021-10/shop.json";
    const PRODUCT_ENDPOINT = "/products/{slug}";
    const OAUTH_AUTHORIZE_ENDPOINT = "/admin/oauth/authorize";
    const OAUTH_ACCESS_TOKEN_ENDPOINT = "/admin/oauth/access_token";
    const RECCURING_CHARGE_ENDPOINT = "/admin/api/2021-10/recurring_application_charges.json";
    const USAGE_CHARGE_ENDPOINT = "/admin/api/2021-10/recurring_application_charges/{id}/usage_charges.json";
    const GET_RECCURING_CHARGE_ENDPOINT = "/admin/api/2021-10/recurring_application_charges/{id}.json";
    const GET_USAGE_CHARGE_ENDPOINT = "/admin/api/2021-10/recurring_application_charges/{charge_id}/usage_charges/{usage_id}.json";
    const DELETE_CHARGE_ENDPOINT = "/admin/api/2021-10/recurring_application_charges/{id}.json";
    const POST_SCRIPT_TAG_ENDPOINT = "/admin/api/2021-10/script_tags.json";
    const POST_PRICE_RULE_ENDPOINT = "/admin/api/2021-10/price_rules.json";
    const POST_DISCOUNT_ENDPOINT = "/admin/api/2021-10/price_rules/{id}/discount_codes.json";
    const GET_CUSTOMER_ENDPOINT = "/admin/api/2021-10/customers/{id}.json";
    const GET_PRODUCT_ENDPOINT = "/admin/api/2021-10/products/{id}.json";
    const GET_ORDER_ENDPOINT = "/admin/api/2021-10/orders/{id}.json?fields=id,currency,name,total_price,confirmed,total_discounts,total_price_usd,discount_codes,checkout_id,customer,line_items,created_at";
    const GET_ORDERS_ENDPOINT = "/admin/api/2021-10/orders.json?fields=id,currency,name,total_price,confirmed,total_discounts,total_price_usd,discount_codes,checkout_id,customer,line_items,created_at";

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
     * @throws LoadConfigurationsFailed
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
                        'tweet.fields'  => 'author_id,created_at,in_reply_to_user_id,geo,referenced_tweets,source,attachments',
                        // 'user.fields'   => 'username,description,created_at',
                        // 'media.fields'  => 'duration_ms,height,media_key,preview_image_url,public_metrics,type,url,width',
                        'max_results'   => self::MAX_RESULTS_PER_RESPONSE
                    ]
                ]
            );

            $data = json_decode($response->getBody(), true);
dd($data);
            if (! isset($data['data'])) {
                throw new Exception();
            }

            return $data['data'];
        } catch (Exception | ClientException | RequestException $e) {
            AppLogger::error($e, 'twitter:fetch-by-hashtags');

            // throw new LoadConfigurationsFailed();
        }
    }
}