<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Listeners;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use BADDIServices\SourceeApp\Models\Tweet;
use Illuminate\Contracts\Queue\ShouldQueue;
use BADDIServices\SourceeApp\Events\AnswerMail;
use BADDIServices\SourceeApp\Models\RequestAnswer;
use BADDIServices\SourceeApp\Services\RequestAnswerService;
use BADDIServices\SourceeApp\Services\TweetService;
use BADDIServices\SourceeApp\Services\UserService;

class AnswerMailFired implements ShouldQueue
{
    public function __construct(
        private UserService $userService,
        private TweetService $tweetService,
        private RequestAnswerService $requestAnswerService
    ) {}
    
    public function handle(AnswerMail $event)
    {
        
        /** @var User|null */
        $user = $this->userService->findById($event->userId);

        if (! $user instanceof User) {
            return;
        }
        
        /** @var Tweet|null */
        $tweet = $this->tweetService->findById($event->tweetId);

        if (! $tweet instanceof Tweet) {
            return;
        }

        /** @var RequestAnswer|null */
        $answer = $this->requestAnswerService->findById($event->answerId);

        if (! $answer instanceof RequestAnswer) {
            return;
        }

        /** @var string */
        $email = $event->email;

        /** @var string|null */
        $from = $event->from;

        $template = 'emails.answer';
        $subject = sprintf('New request answer from %s', $user->getFullName());

        $data = [
            'tweet'     => $tweet,
            'answer'    => $answer,
            'subject'   => $subject
        ];

        Mail::send($template, $data, function($message) use ($user, $email, $from, $subject) {
            $message->to($email);
            $message->from($from ?? $user->email, $user->getFullName());
            $message->replyTo($user->email);
            $message->subject($subject);
        });
    }
}
