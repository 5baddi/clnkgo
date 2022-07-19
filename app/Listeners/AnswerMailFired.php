<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Listeners;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use BADDIServices\ClnkGO\Models\Tweet;
use Illuminate\Contracts\Queue\ShouldQueue;
use BADDIServices\ClnkGO\Events\AnswerMail;
use BADDIServices\ClnkGO\Models\RequestAnswer;
use BADDIServices\ClnkGO\Services\RequestAnswerService;
use BADDIServices\ClnkGO\Services\TweetService;
use BADDIServices\ClnkGO\Services\UserService;

class AnswerMailFired implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

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

        $template = 'emails.notifications.answer';
        $subject = sprintf('New request answer from %s', $user->getFullName());

        $data = [
            'user'      => $user,
            'tweet'     => $tweet,
            'answer'    => $answer,
            'subject'   => $subject
        ];

        Mail::send($template, $data, function($message) use ($user, $email, $from, $subject) {
            $message->to($email);
            $message->from('noreply@clnkgo.com', $user->getFullName());
            $message->replyTo($from ?? $user->email);
            $message->subject($subject);
        });
    }
}
