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
use Illuminate\Support\Facades\Event;
use BADDIServices\ClnkGO\Models\Tweet;
use BADDIServices\ClnkGO\Entities\Alert;
use App\Http\Requests\Requests\SendMailRequest;
use BADDIServices\ClnkGO\Events\AnswerMail;
use BADDIServices\ClnkGO\Models\RequestAnswer;
use BADDIServices\ClnkGO\Services\TweetService;
use BADDIServices\ClnkGO\Services\RequestAnswerService;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;

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
                RequestAnswer::SUBJECT_COLUMN       => $request->input('subject'),
                RequestAnswer::FROM_COLUMN          => $request->input('from'),
                RequestAnswer::ANSWERED_COLUMN      => true,
            ]);

            DB::commit();

            Event::dispatch(
                new AnswerMail($request->input('email'), $this->user->getId(), $tweet->getId(), $answer->getId())
            );

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