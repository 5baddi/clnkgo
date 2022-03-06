<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Services;

use BADDIServices\SourceeApp\Models\TwitterUser;
use BADDIServices\SourceeApp\Repositories\TwitterUserRespository;
use Illuminate\Support\Arr;

class TwitterUserService extends Service
{
    /** @var TwitterUserRespository */
    private $twitterUserRespository;

    public function __construct(TwitterUserRespository $twitterUserRespository)
    {
        $this->twitterUserRespository = $twitterUserRespository;
    }

    public function save(array $attributes): TwitterUser
    {
        $filteredAttributes = collect($attributes)
            ->filter(function ($value) {
                return $value !== null;
            })
            ->only([
                TwitterUser::ID_COLUMN,
                TwitterUser::USERNAME_COLUMN,
                TwitterUser::NAME_COLUMN,
                TwitterUser::VERIFIED_COLUMN,
                TwitterUser::PROTECTED_COLUMN,
                TwitterUser::PROFILE_IMAGE_URL_COLUMN,
                TwitterUser::DESCRIPTION_COLUMN,
                TwitterUser::PINNED_TWEET_ID_COLUMN,
                TwitterUser::LOCATION_COLUMN,
                TwitterUser::URL_COLUMN,
                TwitterUser::REGISTERED_AT_COLUMN,
                TwitterUser::ENTITIES_COLUMN,
                TwitterUser::PUBLIC_METRICS_COLUMN,
                TwitterUser::WITHHELD_COLUMN,
            ]);

        return $this->twitterUserRespository->save($filteredAttributes->toArray());
    }
}