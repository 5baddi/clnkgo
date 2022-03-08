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

        return view('dashboard.index', [
            'title'                             => 'Dashboard',
            'startDate'                         => $startDate,
            'endDate'                           => $endDate,
            'tweets'                            => $this->tweetService->paginate($this->user, $request->query('page')),
            'ordersEarnings'                    =>  0, //$this->statsService->getOrdersEarnings($this->store, $period),
            'ordersEarningsChart'               =>  0, //$this->statsService->getOrdersEarningsChart($this->store, $period),
            'newOrdersCount'                    =>  0, //$this->statsService->getNewOrdersCount($this->store, $period),
            'paidOrdersCommissions'             =>  0, //$this->statsService->getPaidOrdersCommissions($this->store, $period),
            'unpaidOrdersCommissions'           =>  0, //$this->statsService->getUnpaidOrdersCommissions($this->store, $period),
            'topProducts'                       =>  0, //$this->statsService->getOrdersTopProducts($this->store, $period),
            'topAffiliates'                     =>  0, //$this->statsService->getTopAffiliatesByStore($this->store, $period),
            'unreadNotifications'               => $this->user->unreadNotifications,
            'markAsReadNotifications'           => $this->user->notifications->whereNotNull('read_at')->where(User::CREATED_AT, '>=', Carbon::now()->subDays(30)),
        ]);
    }
}