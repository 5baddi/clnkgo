<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Requests;

use Throwable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use BADDIServices\SourceeApp\Models\Tweet;
use BADDIServices\SourceeApp\Entities\Alert;
use BADDIServices\SourceeApp\Services\TweetService;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;
use BADDIServices\SourceeApp\Models\RequestAnswer;
use BADDIServices\SourceeApp\Services\RequestAnswerService;

class MarkAsAnsweredController extends DashboardController
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

        try {
            $this->requestAnswerService->save([
                RequestAnswer::USER_ID_COLUMN   => $this->user->getId(),
                RequestAnswer::TWEET_ID_COLUMN  => $tweet->getId(),
                RequestAnswer::ANSWERED_COLUMN  => true,
            ]);

            return redirect()->back();
        } catch (Throwable $e){
            DB::rollBack();

            return redirect()
                ->back()
                ->with(
                    'alert', 
                    new Alert('An error occurred while marking tweet as answered!')
                )
                ->withInput();
        }
    }
}