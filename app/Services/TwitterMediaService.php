<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Services;

use BADDIServices\SourceeApp\Models\TwitterMedia;
use BADDIServices\SourceeApp\Repositories\TwitterMediaRepository;

class TwitterMediaService extends Service
{
    /** @var TwitterUserRespository */
    private $twitterMediaRepository;

    public function __construct(TwitterMediaRepository $twitterMediaRepository)
    {
        $this->twitterMediaRepository = $twitterMediaRepository;
    }

    public function save(array $attributes): TwitterMedia
    {
        $filteredAttributes = collect($attributes)
            ->filter(function ($value) {
                return $value !== null;
            })
            ->only([
                TwitterMedia::TWEET_ID_COLUMN,
                TwitterMedia::ID_COLUMN,
                TwitterMedia::TYPE_COLUMN,
                TwitterMedia::URL_COLUMN,
                TwitterMedia::PREVIEW_IMAGE_URL_COLUMN,
                TwitterMedia::ALT_TEXT_COLUMN,
                TwitterMedia::HEIGHT_COLUMN,
                TwitterMedia::WIDTH_COLUMN,
                TwitterMedia::DURATION_MS_COLUMN,
                TwitterMedia::PUBLIC_METRICS_COLUMN,
            ]);

        return $this->twitterMediaRepository->save($filteredAttributes->toArray());
    }
}