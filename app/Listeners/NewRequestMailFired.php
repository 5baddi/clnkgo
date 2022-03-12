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
use BADDIServices\SourceeApp\Events\NewRequestMail;

class NewRequestMailFired implements ShouldQueue
{
    /** @var string */
    public const SUBJECT = "New request from ";

    public function handle(NewRequestMail $event)
    {
        /** @var User */
        $user = $event->user;
        
        /** @var Tweet */
        $tweet = $event->tweet;

        $template = 'emails.new-request';

        Mail::send($template, ['user' => $user, 'tweet' => $tweet, 'subject' => self::SUBJECT . $tweet->name ?? '@' . $tweet->username], function($message) use ($user, $tweet) {
            $message->to($user->email);
            $message->subject(self::SUBJECT . $tweet->name ?? '@' . $tweet->username);
        });
    }
}