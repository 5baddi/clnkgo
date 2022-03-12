<?php

/**
 * Sourcee.app
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

class AnswerMailFired implements ShouldQueue
{
    /** @var string */
    public const SUBJECT = "New request answer from ";

    public function handle(AnswerMail $event)
    {
        /** @var string */
        $email = $event->email;
        
        /** @var User */
        $user = $event->user;
        
        /** @var Tweet */
        $tweet = $event->tweet;

        /** @var RequestAnswer */
        $answer = $event->answer;

        $template = 'emails.answer';

        Mail::send($template, ['tweet' => $tweet, 'answer' => $answer, 'subject' => self::SUBJECT . $user->getFullName()], function($message) use ($user, $email) {
            $message->to($email);
            $message->from($user->email);
            $message->subject(self::SUBJECT . $user->getFullName());
        });
    }
}
