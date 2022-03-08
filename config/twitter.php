<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

return [
    'bearer_token'              =>  env('TWITTER_BEARER_TOKEN'),
    'api_key'                   =>  env('TWITTER_API_KEY'),
    'secret'                    =>  env('TWITTER_API_SECRET'),
    'hashtags'                  =>  explode(',', env('TWITTER_MAIN_HASHTAGS', '')),
];