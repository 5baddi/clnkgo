<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard;

use App\Http\Requests\AnalyticsRequest;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;
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

    public function __invoke(AnalyticsRequest $request)
    {
        $tweets = $this->tweetService->paginate($request->query('page'));

        return view('dashboard.paginate', [
            'tweets'    => $tweets
        ]);
    }
}