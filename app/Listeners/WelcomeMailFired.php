<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Listeners;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use BADDIServices\SourceeApp\Models\Store;
use BADDIServices\SourceeApp\Events\WelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeMailFired implements ShouldQueue
{
    /** @var string */
    public const SUBJECT = "Welcome to ";

    public function handle(WelcomeMail $event)
    {
        /** @var Store */
        $store = $event->store;

        /** @var User */
        $user = $event->user;

        $template = 'emails.welcome';

        Mail::send($template, ['store' => $store, 'user' => $user, 'subject' => self::SUBJECT . config('app.name')], function($message) use ($user) {
            $message->to($user->email);
            $message->subject(self::SUBJECT . config('app.name'));
        });
    }
}
