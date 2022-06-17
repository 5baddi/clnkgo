<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Dashboard;

use BADDIServices\ClnkGO\Http\Controllers\DashboardController;
use BADDIServices\ClnkGO\Models\Tweet;
use BADDIServices\ClnkGO\Models\UserFavoriteTweet;
use BADDIServices\ClnkGO\Services\TweetService;
use Illuminate\Http\Response;

class BookmarkTweetController extends DashboardController
{
    /** @var TweetService */
    private $tweetService;

    public function __construct(TweetService $tweetService)
    {
        parent::__construct();

        $this->tweetService = $tweetService;
    }
    
    public function __invoke(string $id)
    {
        $tweet = $this->tweetService->findById($id);
        abort_unless($tweet instanceof Tweet, Response::HTTP_NOT_FOUND);

        UserFavoriteTweet::query()
            ->updateOrCreate(
                [
                    UserFavoriteTweet::USER_ID_COLUMN   => $this->user->getId(),
                    UserFavoriteTweet::TWEET_ID_COLUMN  => $tweet->getId(),
                ],
                [
                    UserFavoriteTweet::USER_ID_COLUMN   => $this->user->getId(),
                    UserFavoriteTweet::TWEET_ID_COLUMN  => $tweet->getId(),
                ]
            );

        return redirect()->back();
    }
}