<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Requests;

use Illuminate\Http\Response;
use BADDIServices\SourceeApp\Models\Tweet;
use BADDIServices\SourceeApp\Services\TweetService;
use BADDIServices\SourceeApp\Models\UserFavoriteTweet;
use BADDIServices\SourceeApp\Services\RequestAnswerService;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class ShowRequestController extends DashboardController
{
    /** @var TweetService */
    private $tweetService;

    /** @var RequestAnswerService */
    private $requestAnswerService;

    public function __construct(TweetService $tweetService, RequestAnswerService $requestAnswerService)
    {
        parent::__construct();

        $this->tweetService = $tweetService;
        $this->requestAnswerService = $requestAnswerService;
    }
    
    public function __invoke(string $id)
    {
        $tweet = $this->tweetService->findById($id);
        abort_unless($tweet instanceof Tweet, Response::HTTP_NOT_FOUND);

        $answer = $this->requestAnswerService->find($this->user, $tweet);
        $inFavorite = $this->user->favorite->where(UserFavoriteTweet::TWEET_ID_COLUMN, $tweet->getId())->first() instanceof UserFavoriteTweet;

        return view('dashboard.requests.show', [
            'title'         => 'Respond to request',
            'tweet'         => $tweet,
            'answer'        => $answer,
            'inFavorite'    => $inFavorite
        ]);
    }
}