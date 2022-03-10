<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Requests;

use BADDIServices\SourceeApp\Http\Controllers\DashboardController;
use BADDIServices\SourceeApp\Models\Tweet;
use BADDIServices\SourceeApp\Services\RequestAnswerService;
use BADDIServices\SourceeApp\Services\TweetService;
use Illuminate\Http\Response;

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

        return view('dashboard.requests.show', [
            'title'     => 'Respond to request',
            'tweet'     => $tweet,
            'answer'    => $answer
        ]);
    }
}