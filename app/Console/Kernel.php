<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\Twitter\FetchUserProfile;
use App\Console\Commands\Twitter\FetchLatestTweets;
use App\Console\Commands\MailUserWhenThereNewRequest;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        FetchLatestTweets::class,
        FetchUserProfile::class,
        MailUserWhenThereNewRequest::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (app()->environment() === 'production') {
            $schedule->command('queue:work --timeout=2000 --sleep=3 --tries=3 --daemon')->everyMinute()->withoutOverlapping()->runInBackground();
        
            $schedule->command('twitter:latest-tweets')->everyFifteenMinutes()->withoutOverlapping();
            $schedule->command('twitter:fetch-user-profile')->hourly()->withoutOverlapping();

            $schedule->command('mail:new-request')->hourly()->withoutOverlapping();
            $schedule->command('app:update-most-used-keywords')->weekly();
        } else {
            $schedule->command('queue:work --timeout=2000 --sleep=3 --tries=3 --daemon')->everyFourHours()->withoutOverlapping()->runInBackground();
        
            $schedule->command('twitter:latest-tweets')->daily()->withoutOverlapping();
            $schedule->command('twitter:fetch-user-profile')->daily()->withoutOverlapping();

            $schedule->command('mail:new-request')->daily()->withoutOverlapping();
            $schedule->command('app:update-most-used-keywords')->weekly();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected function bootstrappers()
    {
        return array_merge(
            [\Bugsnag\BugsnagLaravel\OomBootstrapper::class],
            parent::bootstrappers(),
        );
    }
}
