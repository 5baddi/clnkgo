<?php

namespace App\Providers;

// use Illuminate\Auth\Events\Registered;
use BADDIServices\SocialRocket\Events\WelcomeMail;
use BADDIServices\SocialRocket\Events\NewOrderCommission;
use BADDIServices\SocialRocket\Listeners\WelcomeMailFired;
// use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use BADDIServices\SocialRocket\Listeners\NewOrderCommissionFired;
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
        
        NewOrderCommission::class => [
            NewOrderCommissionFired::class,
        ]
    ];

    public function boot()
    {
        
    }
}
