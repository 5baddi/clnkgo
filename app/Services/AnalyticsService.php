<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Services;

use App\Models\User;
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
    
    public function keywordsMatch(User $user): int
    {
        if (count($user->getKeywords()) === 0) {
            return 0;
        }

        return $this->tweetRespository->keywordsMatch($user->getKeywords());
    }
}