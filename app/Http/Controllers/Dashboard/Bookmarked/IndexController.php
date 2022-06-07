<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Bookmarked;

use BADDIServices\SourceeApp\Services\TweetService;
use App\Http\Requests\Dashboard\BookmarkedTweetsRequest;
use BADDIServices\SourceeApp\Http\Filters\Tweet\TweetQueryFilter;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class IndexController extends DashboardController
{
    public function __construct(
        private TweetService $tweetService
    ) {
        parent::__construct();
    }

    public function __invoke(BookmarkedTweetsRequest $request, TweetQueryFilter $queryFilter)
    {
        $tweets = $this->tweetService->paginate($queryFilter);

        return $this->render('dashboard.answered.index', [
            'title'                             => 'Saved',
            'user'                              => $this->user,
            'category'                          => $request->query('category'),
            'term'                              => $request->query('term'),
            'tweets'                            => $tweets
        ]);
    }
}