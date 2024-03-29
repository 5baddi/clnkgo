<?php

namespace App\Providers;

// use Illuminate\Auth\Events\Registered;
use BADDIServices\ClnkGO\Events\AnswerMail;
use BADDIServices\ClnkGO\Events\WelcomeMail;
use BADDIServices\ClnkGO\Events\NewRequestMail;
use BADDIServices\ClnkGO\Events\Auth\ResetPassword;
use BADDIServices\ClnkGO\Listeners\AnswerMailFired;
use BADDIServices\ClnkGO\Listeners\WelcomeMailFired;
use BADDIServices\ClnkGO\Listeners\NewRequestMailFired;
use BADDIServices\ClnkGO\Listeners\Auth\ResetPasswordFired;
// use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use BADDIServices\ClnkGO\Events\Subscription\SubscriptionActivated;
use BADDIServices\ClnkGO\Events\Subscription\SubscriptionCancelled;
use BADDIServices\ClnkGO\Events\LinkedEmail\LinkedEmailConfirmationMail;
use BADDIServices\ClnkGO\Events\Marketing\CPALeadOfferMailWasSent;
use BADDIServices\ClnkGO\Events\Marketing\CPALeadOfferMailWasViewed;
use BADDIServices\ClnkGO\Events\Marketing\MailingListEmailWasVerified;
use BADDIServices\ClnkGO\Listeners\LinkedEmail\LinkedEmailConfirmationMailFired;
use BADDIServices\ClnkGO\Listeners\Marketing\CPALeadOfferMailWasSentFired;
use BADDIServices\ClnkGO\Listeners\Marketing\CPALeadOfferMailWasViewedFired;
use BADDIServices\ClnkGO\Listeners\Marketing\MailingListEmailWasVerifiedFired;
use BADDIServices\ClnkGO\Listeners\Subscription\SubscriptionActivatedFired;
use BADDIServices\ClnkGO\Listeners\Subscription\SubscriptionCancelledFired;
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

        CPALeadOfferMailWasSent::class => [
            CPALeadOfferMailWasSentFired::class,
        ],
        
        CPALeadOfferMailWasViewed::class => [
            CPALeadOfferMailWasViewedFired::class,
        ],

        MailingListEmailWasVerified::class => [
            MailingListEmailWasVerifiedFired::class,
        ],
    ];

    public function boot()
    {
        
    }
}
