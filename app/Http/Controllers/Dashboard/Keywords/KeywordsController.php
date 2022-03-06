<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Keywords;

use BADDIServices\SourceeApp\Http\Controllers\DashboardController;
use BADDIServices\SourceeApp\Services\TweetService;

class KeywordsController extends DashboardController
{
    /** @var TweetService */
    private $tweetService;

    public function __construct(TweetService $tweetService)
    {
        parent::__construct();
        
        $this->tweetService = $tweetService;
    }

    public function __invoke()
    {
        return view('dashboard.keywords.index', [
            'title'             => 'Your Keywords 🔑',
            'keywords'          => $this->user->getKeywords(),
            'hashtags'          => $this->tweetService->getHashtags()
        ]);
    }
}