<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

return [
    'support_email'     => env('SUPPORT_EMAIL'),
    'help_url'          => env('HELP_URL'),
    'setup_guide'       => env('SETUP_GUIDE'),
    'zendesk_key'       => env('ZENDESK_KEY'),
    'version'           => env('APP_VERSION', '1.0.0'),
    'hcaptcha_verify_endpoint'           => env('HCAPTCHA_VERIFY_ENDPOINT'),
    'hcaptcha_js_endpoint'               => env('HCAPTCHA_JS_ENDPOINT'),
    'hcaptcha_secret'                    => env('HCAPTCHA_SECRET'),
    'hcaptcha_site_key'                  => env('HCAPTCHA_SITE_KEY'),
    'hcaptcha_enabled'                   => env('HCAPTCHA_FEATURE_ENABLED', false),
];