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
use BADDIServices\SourceeApp\Services\TweetService;

class IndexController extends DashboardController
{
    /** @var TweetService */
    private $tweetService;

    public function __construct(TweetService $tweetService)
    {
        parent::__construct();

        $this->tweetService = $tweetService;
    }

    public function __invoke(AnalyticsRequest $request)
    {
        $tweets = $this->tweetService->paginate($request->query('page'), false);
        $countOfLast24Hours = $tweets->getCollection()
            ->filter(function ($tweet) {
                return $tweet->published_at->greaterThan(Carbon::now()->subHours(24)) && ($tweet->due_at === null || Carbon::now()->endOfDay()->greaterThan($tweet->due_at));
            })
            ->count();

        return view('dashboard.index', [
            'title'                             => 'Dashboard',
            'user'                              => $this->user,
            'tweets'                            => $tweets,
            'liveRequests'                      => $tweets->total(),
            'last24hRequests'                   => $countOfLast24Hours,
            'unreadNotifications'               => $this->user->unreadNotifications,
            'markAsReadNotifications'           => $this->user->notifications->whereNotNull('read_at')->where(User::CREATED_AT, '>=', Carbon::now()->subDays(30)),
        ]);
    }
}