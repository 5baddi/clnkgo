<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Services;

use BADDIServices\SourceeApp\Repositories\TweetRespository;

class AnalyticsService extends Service
{
    public function __construct(
        private TweetRespository $tweetRespository
    ) {}

    public function last24hRequests(): int
    {
        return $this->tweetRespository->last24hRequests();
    }

    public function liveRequests(): int
    {
        return $this->tweetRespository->liveRequests();
    }
}