<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

return [
    'bearer_token'              =>  env('TWITTER_BEARER_TOKEN'),
    'hashtags'                  =>  explode(',', env('TWITTER_MAIN_HASHTAGS', '')),
];