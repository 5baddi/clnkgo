<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Services;

use BADDIServices\SourceeApp\Models\Tweet;
use BADDIServices\SourceeApp\Repositories\TweetRespository;
use Illuminate\Support\Arr;

class TweetService extends Service
{
    /** @var TweetRespository */
    private $tweetRespository;

    public function __construct(TweetRespository $tweetRespository)
    {
        $this->tweetRespository = $tweetRespository;
    }

    public function save(string $hashtag, array $attributes): Tweet
    {
        $attributes[Tweet::HASHTAG_COLUMN] = $hashtag;

        return $this->tweetRespository->save($attributes);
    }
}