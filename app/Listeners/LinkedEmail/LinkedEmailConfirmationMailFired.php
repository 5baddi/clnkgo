<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Listeners\LinkedEmail;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use BADDIServices\SourceeApp\Services\UserService;
use BADDIServices\SourceeApp\Models\UserLinkedEmail;
use BADDIServices\SourceeApp\Events\LinkedEmail\LinkedEmailConfirmationMail;

class LinkedEmailConfirmationMailFired implements ShouldQueue
{
    /** @var string */
    public const SUBJECT = "New Email linked ready for confirmation!";

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

        $data = [
            'user'          => $user,
            'linkedEmail'   => $linkedEmail,
            'subject'       => self::SUBJECT
        ];

        Mail::send($template, $data, function($message) use ($linkedEmail) {
            $message->to($linkedEmail->email);
            $message->subject(self::SUBJECT);
        });
    }
}