<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Services;

class CategoryService extends Service
{
    /** @var CategoryRespository */
    private $categoryRespository;

    public function __construct(TwitterUserRespository $twitterUserRespository)
    {
        $this->twitterUserRespository = $twitterUserRespository;
    }
}