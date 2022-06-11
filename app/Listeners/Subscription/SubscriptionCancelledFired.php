<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Listeners\Subscription;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use BADDIServices\SourceeApp\Models\Subscription;
use BADDIServices\SourceeApp\Services\UserService;
use BADDIServices\SourceeApp\Services\SubscriptionService;
use BADDIServices\SourceeApp\Events\Subscription\SubscriptionCancelled;

class SubscriptionCancelledFired implements ShouldQueue
{
    public function __construct(
        private UserService $userService,
        private SubscriptionService $subscriptionService
    ) {}
    
    public function handle(SubscriptionCancelled $event)
    {
        /** @var User|null */
        $user = $this->userService->findById($event->userId);

        if (! $user instanceof User) {
            return;
        }

        /** @var Subscription|null */
        $subscription = $this->subscriptionService->findById($event->subscriptionId);

        if (! $subscription instanceof Subscription) {
            return;
        }

        $template = 'emails.subscription.cancelled';
        $subject = sprintf('Your subscription to %s plan has been cancelled!', ucwords($subscription->pack->name));

        $data = [
            'user'          => $user, 
            'subscription'  => $subscription
        ];

        Mail::send($template, $data, function($message) use ($user, $subject) {
            $message->to($user->email);
            $message->subject($subject);
        });
    }
}