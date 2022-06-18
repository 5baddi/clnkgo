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
use BADDIServices\ClnkGO\Services\AnalyticsService;
use BADDIServices\ClnkGO\Services\TweetService;

class IndexController extends DashboardController
{
    public function __construct(
        private TweetService $tweetService,
        private AnalyticsService $analyticsService
    ) {
        parent::__construct();
    }

    public function __invoke(AnalyticsRequest $request, TweetQueryFilter $queryFilter)
    {
        $tweets = $this->tweetService->paginate($queryFilter);

        return $this->render('dashboard.index', [
            'title'                             => 'Dashboard',
            'category'                          => $request->query('category'),
            'sort'                              => $request->query('sort'),
            'term'                              => $request->query('term'),
            'tweets'                            => $tweets,
            'liveRequests'                      => $this->analyticsService->liveRequests(),
            'last24hRequests'                   => $this->analyticsService->last24hRequests(),
            'keywordsMatch'                     => $this->analyticsService->keywordsMatch($this->user)
        ]);
    }
}