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
use BADDIServices\SourceeApp\Services\StatsService;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;
use BADDIServices\SourceeApp\Services\TweetService;

class IndexController extends DashboardController
{
    /** @var StatsService */
    private $statsService;

    /** @var TweetService */
    private $tweetService;

    public function __construct(StatsService $statsService, TweetService $tweetService)
    {
        parent::__construct();

        $this->statsService = $statsService;
        $this->tweetService = $tweetService;
    }

    public function __invoke(AnalyticsRequest $request)
    {
        $last7Days = $this->statsService->getLast7DaysPeriod();
        $startDate = $request->input('start-date', $last7Days->copy()->getStartDate()->format('Y/m/d'));
        $endDate = $request->input('end-date', $last7Days->copy()->getEndDate()->format('Y/m/d'));

        $period = $this->statsService->getPeriod(Carbon::parse($startDate . ' 00:00:00'), Carbon::parse($endDate . ' 23:59:59'));

        $tweets = $this->tweetService->paginate($request->query('page'));
        $countOfLast24Hours = $tweets->getCollection()
            ->filter(function ($tweet) {
                return $tweet->published_at->greaterThan(Carbon::now()->subHours(24)) && ($tweet->due_at === null || Carbon::now()->endOfDay()->greaterThan($tweet->due_at));
            })
            ->count();

        return view('dashboard.index', [
            'title'                             => 'Dashboard',
            'startDate'                         => $startDate,
            'endDate'                           => $endDate,
            'tweets'                            => $tweets,
            'liveRequests'                      => $tweets->total(),
            'last24hRequests'                   => $countOfLast24Hours,
            'unreadNotifications'               => $this->user->unreadNotifications,
            'markAsReadNotifications'           => $this->user->notifications->whereNotNull('read_at')->where(User::CREATED_AT, '>=', Carbon::now()->subDays(30)),
        ]);
    }
}