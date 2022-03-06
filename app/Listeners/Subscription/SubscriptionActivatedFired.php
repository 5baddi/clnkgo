<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Listeners\Subscription;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use BADDIServices\SourceeApp\Models\Subscription;
use BADDIServices\SourceeApp\Events\Subscription\SubscriptionActivated;

class SubscriptionActivatedFired implements ShouldQueue
{
    /** @var string */
    public const SUBJECT = "Your subscription to %s plan has been activated!";

    public function handle(SubscriptionActivated $event)
    {
        /** @var User */
        $user = $event->user;

        /** @var Subscription */
        $subscription = $event->subscription;

        $subject = sprintf(self::SUBJECT, ucwords($subscription->pack->name));

        Mail::send('emails.subscription.activated', ['user' => $user, 'subscription' => $subscription], function($message) use ($user, $subject) {
            $message->to($user->email);
            $message->subject($subject);
        });
    }
}