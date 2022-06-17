<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Services;

class CategoryService extends Service
{
    /** @var CategoryRespository */
    private $categoryRespository;

    public function __construct(TwitterUserRespository $twitterUserRespository)
    {
        $this->twitterUserRespository = $twitterUserRespository;
    }
}