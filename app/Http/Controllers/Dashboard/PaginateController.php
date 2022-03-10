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
        $tweets = $this->tweetService->paginate(
            $request->query('page'), 
            $request->query('term'), 
            $request->query('sort'), 
            $request->query('filter'), 
            $request->query('filter') !== '-1' ? $this->user : null
        );

        return view('dashboard.paginate', [
            'user'      => $this->user,
            'tweets'    => $tweets
        ]);
    }
}