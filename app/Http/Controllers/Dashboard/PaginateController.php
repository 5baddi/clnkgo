<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard;

use BADDIServices\SourceeApp\Http\Controllers\DashboardController;
use BADDIServices\SourceeApp\Http\Filters\Tweet\TweetQueryFilter;
use BADDIServices\SourceeApp\Services\TweetService;

class PaginateController extends DashboardController
{
    /** @var TweetService */
    private $tweetService;

    public function __construct(TweetService $tweetService)
    {
        parent::__construct();

        $this->tweetService = $tweetService;
    }

    public function __invoke(TweetQueryFilter $queryFilter)
    {
        $tweets = $this->tweetService->paginate($queryFilter);

        return $this->render('dashboard.paginate', [
            'user'      => $this->user,
            'tweets'    => $tweets
        ]);
    }
}