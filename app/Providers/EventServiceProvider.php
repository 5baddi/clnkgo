<?php

namespace App\Providers;

// use Illuminate\Auth\Events\Registered;
use BADDIServices\SourceeApp\Events\AnswerMail;
use BADDIServices\SourceeApp\Events\WelcomeMail;
use BADDIServices\SourceeApp\Events\NewRequestMail;
use BADDIServices\SourceeApp\Events\Auth\ResetPassword;
use BADDIServices\SourceeApp\Listeners\AnswerMailFired;
use BADDIServices\SourceeApp\Listeners\WelcomeMailFired;
use BADDIServices\SourceeApp\Listeners\NewRequestMailFired;
use BADDIServices\SourceeApp\Listeners\Auth\ResetPasswordFired;
// use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use BADDIServices\SourceeApp\Events\Subscription\SubscriptionActivated;
use BADDIServices\SourceeApp\Events\Subscription\SubscriptionCancelled;
use BADDIServices\SourceeApp\Events\LinkedEmail\LinkedEmailConfirmationMail;
use BADDIServices\SourceeApp\Listeners\LinkedEmail\LinkedEmailConfirmationMailFired;
use BADDIServices\SourceeApp\Listeners\Subscription\SubscriptionActivatedFired;
use BADDIServices\SourceeApp\Listeners\Subscription\SubscriptionCancelledFired;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /** @var array */
    protected $listen = [
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],

        WelcomeMail::class => [
            WelcomeMailFired::class,
        ],

        AnswerMail::class => [
            AnswerMailFired::class,
        ], 
        
        NewRequestMail::class => [
            NewRequestMailFired::class,
        ],

        ResetPassword::class => [
            ResetPasswordFired::class,
        ],

        SubscriptionActivated::class => [
            SubscriptionActivatedFired::class,
        ],
        
        SubscriptionCancelled::class => [
            SubscriptionCancelledFired::class,
        ],

        LinkedEmailConfirmationMail::class => [
            LinkedEmailConfirmationMailFired::class,
        ],
    ];

    public function boot()
    {
        
    }
}
