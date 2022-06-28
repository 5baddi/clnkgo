<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Listeners\LinkedEmail;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use BADDIServices\ClnkGO\Services\UserService;
use BADDIServices\ClnkGO\Models\UserLinkedEmail;
use BADDIServices\ClnkGO\Events\LinkedEmail\LinkedEmailConfirmationMail;

class LinkedEmailConfirmationMailFired implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    public function __construct(private UserService $userService) {}

    public function handle(LinkedEmailConfirmationMail $event)
    {
        /** @var UserLinkedEmail|null */
        $linkedEmail = $this->userService->findLinkedEmailById($event->linkedEmailId);

        if (! $linkedEmail instanceof UserLinkedEmail) {
            return;
        }

        /** @var User|null */
        $user = $this->userService->findById($linkedEmail->getUserId());

        if (! $user instanceof User) {
            return;
        }

        $template = 'emails.linked-email.confirmation';
        $subject = 'New Email linked ready for confirmation!';

        $data = [
            'user'          => $user,
            'linkedEmail'   => $linkedEmail,
            'subject'       => $subject
        ];

        Mail::send($template, $data, function($message) use ($linkedEmail, $subject) {
            $message->to($linkedEmail->email);
            $message->subject($subject);
        });
    }
}
