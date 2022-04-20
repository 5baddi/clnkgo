<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\User;
use App\Http\Requests\AnalyticsRequest;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;
use BADDIServices\SourceeApp\Services\AnalyticsService;
use BADDIServices\SourceeApp\Services\TweetService;

class IndexController extends DashboardController
{
    public function __construct(
        private TweetService $tweetService,
        private AnalyticsService $analyticsService
    ) {
        parent::__construct();
    }

    public function __invoke(AnalyticsRequest $request)
    {
        $tweets = $this->tweetService->paginate(
            $request->query('page'), 
            $request->query('term'), 
            $request->query('sort'), 
            $request->query('category'),
            $request->query('filter'), 
            $request->query('filter') !== '-1' ? $this->user : null
        );

        return view('dashboard.index', [
            'title'                             => 'Dashboard',
            'user'                              => $this->user,
            'category'                          => $request->query('category'),
            'sort'                              => $request->query('sort'),
            'term'                              => $request->query('term'),
            'filter'                            => $request->query('filter'),
            'tweets'                            => $tweets,
            'liveRequests'                      => $this->analyticsService->liveRequests(),
            'last24hRequests'                   => $this->analyticsService->last24hRequests(),
            'keywordMatch'                      => $this->analyticsService->last24hRequests(),
            'unreadNotifications'               => $this->user->unreadNotifications,
            'markAsReadNotifications'           => $this->user->notifications->whereNotNull('read_at')->where(User::CREATED_AT, '>=', Carbon::now()->subDays(30)),
        ]);
    }
}