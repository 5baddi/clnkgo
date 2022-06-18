<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Dashboard;

use App\Http\Requests\AnalyticsRequest;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;
use BADDIServices\ClnkGO\Http\Filters\Tweet\TweetQueryFilter;
use BADDIServices\ClnkGO\Services\TweetService;

class PaginateController extends DashboardController
{
    /** @var TweetService */
    private $tweetService;

    public function __construct(TweetService $tweetService)
    {
        parent::__construct();

        $this->tweetService = $tweetService;
    }

    public function __invoke(AnalyticsRequest $request, TweetQueryFilter $queryFilter)
    {
        $tweets = $this->tweetService->paginate($queryFilter);

        return $this->render('dashboard.paginate', [
            'tweets'    => $tweets
        ]);
    }
}