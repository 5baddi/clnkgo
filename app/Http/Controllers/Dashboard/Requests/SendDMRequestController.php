<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Dashboard\Requests;

use Throwable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use BADDIServices\ClnkGO\Models\Tweet;
use BADDIServices\ClnkGO\Entities\Alert;
use App\Http\Requests\Requests\SendDMRequest;
use BADDIServices\ClnkGO\Services\TweetService;
use BADDIServices\ClnkGO\Domains\TwitterService;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;
use BADDIServices\ClnkGO\Models\RequestAnswer;
use BADDIServices\ClnkGO\Services\RequestAnswerService;

class SendDMRequestController extends DashboardController
{
    /** @var TweetService */
    private $tweetService;

    /** @var TwitterService */
    private $twitterService;

    /** @var RequestAnswerService */
    private $requestAnswerService;

    public function __construct(TweetService $tweetService, TwitterService $twitterService, RequestAnswerService $requestAnswerService)
    {
        parent::__construct();

        $this->tweetService = $tweetService;
        $this->twitterService = $twitterService;
        $this->requestAnswerService = $requestAnswerService;
    }
    
    public function __invoke(string $id, SendDMRequest $request)
    {
        $tweet = $this->tweetService->findById($id);
        abort_unless($tweet instanceof Tweet, Response::HTTP_NOT_FOUND);

        try {
            $this->requestAnswerService->save([
                RequestAnswer::USER_ID_COLUMN   => $this->user->getId(),
                RequestAnswer::TWEET_ID_COLUMN  => $tweet->getId(),
                RequestAnswer::CONTENT_COLUMN   => $request->input('content'),
                RequestAnswer::ANSWERED_COLUMN  => true,
            ]);

            $link = $this->twitterService->getDMLink($tweet->author_id, $request->input('content'));

            return redirect()->to($link);
        } catch (Throwable $e){
            DB::rollBack();

            return redirect()
                ->back()
                ->with(
                    'alert', 
                    new Alert('An error occurred while sending direct message!')
                )
                ->withInput();
        }
    }
}