<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

return [
    'client_id'         =>  env('PAYPAL_CLIENT_ID'),
    'secret_key'        =>  env('PAYPAL_SECRET_KEY'),
    'account_email'     =>  env('PAYPAL_ACCOUNT_EMAIL'),
    'sandbox_enabled'   =>  env('PAYPAL_SANDBOX_ENABLED', true),
];