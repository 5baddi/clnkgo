<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Admin\Tweets;

use Illuminate\Http\Request;
use BADDIServices\ClnkGO\Http\Controllers\AdminController;
use BADDIServices\ClnkGO\Http\Filters\Admin\Tweet\TweetQueryFilter;
use BADDIServices\ClnkGO\Models\Tweet;
use BADDIServices\ClnkGO\Services\TweetService;

class IndexController extends AdminController
{
    public function __construct(
        private TweetService $tweetService
    ) {
        parent::__construct();
    }
    
    public function __invoke(Request $request, TweetQueryFilter $queryFilter)
    {
        $source = $request->query('source', Tweet::TWITTER_SOURCE);

        $request->merge(['source' => $source]);

        $tweets = $this->tweetService->paginate($queryFilter);

        return $this->render(
            'admin.tweets.index',
            [
                'title'             => 'Manage queries',
                'source'            => $source,
                'tweets'            => $tweets
            ]
        );
    }
}