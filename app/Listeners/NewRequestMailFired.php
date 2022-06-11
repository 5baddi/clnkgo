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
use BADDIServices\SourceeApp\Services\UserService;
use BADDIServices\SourceeApp\Events\NewRequestMail;
use BADDIServices\SourceeApp\Services\TweetService;

class NewRequestMailFired implements ShouldQueue
{
    public function __construct(
        private UserService $userService,
        private TweetService $tweetService
    ) {}

    public function handle(NewRequestMail $event)
    {
        /** @var User|null */
        $user = $this->userService->findById($event->userId);

        if (! $user instanceof User) {
            return;
        }
        
        /** @var Tweet|null */
        $tweet = $event->tweetId;

        if (! $tweet instanceof Tweet) {
            return;
        }

        $template = 'emails.new-request';
        $subject = sprintf('New request from %s', ($tweet->author->name ?? '@' . $tweet->author->username));

        $data = [
            'user'      => $user,
            'tweet'     => $tweet,
            'subject'   => $subject
        ];

        Mail::send($template, $data, function($message) use ($user, $subject) {
            $message->to($user->email);
            $message->subject($subject);
        });
    }
}