<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Dashboard\Answered;

use BADDIServices\ClnkGO\Services\TweetService;
use App\Http\Requests\Dashboard\AnsweredTweetsRequest;
use BADDIServices\ClnkGO\Http\Filters\Tweet\TweetQueryFilter;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;

class IndexController extends DashboardController
{
    public function __construct(
        private TweetService $tweetService
    ) {
        parent::__construct();
    }

    public function __invoke(AnsweredTweetsRequest $request, TweetQueryFilter $queryFilter)
    {
        $tweets = $this->tweetService->paginate($queryFilter);

        return $this->render('dashboard.answered.index', [
            'title'                             => 'Sent',
            'user'                              => $this->user,
            'category'                          => $request->query('category'),
            'term'                              => $request->query('term'),
            'tweets'                            => $tweets
        ]);
    }
}