<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Listeners;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use BADDIServices\SourceeApp\Events\WelcomeMail;
use BADDIServices\SourceeApp\Services\UserService;

class WelcomeMailFired implements ShouldQueue
{
    public function __construct(private UserService $userService) {}

    public function handle(WelcomeMail $event)
    {
        /** @var User|null */
        $user = $this->userService->findById($event->userId);

        if (! $user instanceof User) {
            return;
        }

        $template = 'emails.welcome';
        $subject = sprintf('Welcome to %s', config('app.name'));

        $data = [
            'user'      => $user,
            'subject'   => $subject
        ];

        Mail::send($template, $data, function($message) use ($user, $subject) {
            $message->to($user->email);
            $message->subject($subject);
        });
    }
}
