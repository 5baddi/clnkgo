<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Admin\Tweets;

use Illuminate\Http\Request;
use BADDIServices\SourceeApp\Http\Controllers\AdminController;
use BADDIServices\SourceeApp\Http\Filters\Admin\Tweet\TweetQueryFilter;
use BADDIServices\SourceeApp\Models\Tweet;
use BADDIServices\SourceeApp\Services\TweetService;

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