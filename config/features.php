<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

return [
    'extract_due_date'  =>  [
        'enabled'       => env('EXTRACT_DUE_DATE_FEATURE_ENABLED', false),
        'for'           => env('EXTRACT_DUE_DATE_FEATURE_ENABLED_FOR'),
    ],
    'fetch_tweets'      =>  [
        'enabled'       => env('FETCH_TWEETS_FEATURE_ENABLED', false),
        'for'           => env('FETCH_TWEETS_FEATURE_ENABLED_FOR'),
    ],
];