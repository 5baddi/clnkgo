<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp;

class App
{
    public const PAGINATION_LIMIT = 10;
    public const CHUNK_SIZE = 1000;
    public const TWEET_CHARACTERS_LIMIT = 280;
    public const MAX_KEYWORDS = 30;
    public const MAX_CANNED_RESPONSES = 10;

    public const APP_MOST_USED_KEYWORDS = 'app_most_used_keywords';

    public const EMAIL_PROVIDERS = [
        'gmail',
        'outlook',
        'yahoo',
        'hotmail'
    ];
}