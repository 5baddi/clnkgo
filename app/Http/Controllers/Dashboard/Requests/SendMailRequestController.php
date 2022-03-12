<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Dashboard\Requests;

use Throwable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use BADDIServices\SourceeApp\Models\Tweet;
use BADDIServices\SourceeApp\Entities\Alert;
use App\Http\Requests\Requests\SendMailRequest;
use BADDIServices\SourceeApp\Events\AnswerMail;
use BADDIServices\SourceeApp\Models\RequestAnswer;
use BADDIServices\SourceeApp\Services\TweetService;
use BADDIServices\SourceeApp\Services\RequestAnswerService;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;

class SendMailRequestController extends DashboardController
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
    
    public function __invoke(string $id, SendMailRequest $request)
    {
        $tweet = $this->tweetService->findById($id);
        abort_unless($tweet instanceof Tweet, Response::HTTP_NOT_FOUND);

        try {
            DB::beginTransaction();

            $answer = $this->requestAnswerService->save([
                RequestAnswer::USER_ID_COLUMN       => $this->user->getId(),
                RequestAnswer::TWEET_ID_COLUMN      => $tweet->getId(),
                RequestAnswer::MAIL_SENT_AT_COLUMN  => now(),
                RequestAnswer::CONTENT_COLUMN       => $request->input('content'),
                RequestAnswer::EMAIL_COLUMN         => $request->input('email'),
            ]);

            DB::commit();

            Event::dispatch(new AnswerMail($request->input('email'), $this->user, $tweet, $answer));

            return redirect()
                ->back()
                ->with(
                    'alert', 
                    new Alert('Great! The mail will be send in few seconds...', 'success')
                );
        } catch (Throwable $e){
            DB::rollBack();

            return redirect()
                ->back()
                ->with(
                    'alert', 
                    new Alert('An error occurred while sending the mail!')
                )
                ->withInput();
        }
    }
}