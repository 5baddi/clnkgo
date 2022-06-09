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
    public const PAYPAL_CLIENT_ID = 'paypal_client_id';

    public const DEFAULT_EMAIL_PROVIDERS = [
        'gmail.com',
        'outlook.com',
        'yahoo.com',
        'hotmail.com',
        'icloud.com',
        'live.com',
        'msn.com'
    ];
    
    public const DEFAULT_MAIN_HASHTAGS = [
        'journorequest',
        'journorequests',
        'prrequest',
        'prrequests'
    ];

    // Featues names
    public const EXTRACT_DUE_DATE_FEATURE = 'extract_due_date';
    public const FETCH_TWEETS_FEATURE = 'fetch_tweets';
    public const MARK_AS_ANSWERED_FEATURE = 'mark_as_answered';
    public const JOURNALIST_AREA_FEATURE = 'journalist_area';
    public const REPORT_BUGS_WITH_GLEAP_FEATURE = 'report_bugs_with_gleap';
}