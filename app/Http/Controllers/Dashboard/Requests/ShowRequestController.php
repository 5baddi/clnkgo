<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Dashboard\Requests;

use Illuminate\Http\Response;
use BADDIServices\ClnkGO\Models\Tweet;
use BADDIServices\ClnkGO\Services\TweetService;
use BADDIServices\ClnkGO\Models\UserFavoriteTweet;
use BADDIServices\ClnkGO\Services\RequestAnswerService;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;
use BADDIServices\ClnkGO\Models\UserLinkedEmail;
use BADDIServices\ClnkGO\Services\SavedResponseService;

class ShowRequestController extends DashboardController
{
    /** @var TweetService */
    private $tweetService;

    /** @var RequestAnswerService */
    private $requestAnswerService;
    
    /** @var SavedResponseService */
    private $savedResponseService;

    public function __construct(TweetService $tweetService, RequestAnswerService $requestAnswerService, SavedResponseService $savedResponseService)
    {
        parent::__construct();

        $this->tweetService = $tweetService;
        $this->requestAnswerService = $requestAnswerService;
        $this->savedResponseService = $savedResponseService;
    }
    
    public function __invoke(string $id)
    {
        $tweet = $this->tweetService->findById($id);
        abort_unless($tweet instanceof Tweet, Response::HTTP_NOT_FOUND);

        $authorTweetsCount = $this->tweetService->getAuthorTweetsCount(
            $tweet->getAuthorId(),
            $tweet->getId()
        );

        $cannedResponses = $this->savedResponseService->getByUser($this->user);
        $answer = $this->requestAnswerService->find($this->user, $tweet);
        $inFavorite = $this->user->favorites->where(UserFavoriteTweet::TWEET_ID_COLUMN, $tweet->getId())->first() instanceof UserFavoriteTweet;

        $emails = $this->user->linkedEmails->whereNotNull(UserLinkedEmail::CONFIRMED_AT_COLUMN)->pluck('email')->toArray();

        return $this->render('dashboard.requests.show', [
            'title'             => 'Respond to request',
            'tweet'             => $tweet,
            'authorTweetsCount' => $authorTweetsCount,
            'answer'            => $answer,
            'inFavorite'        => $inFavorite,
            'cannedResponses'   => $cannedResponses,
            'emails'            => $emails
        ]);
    }
}