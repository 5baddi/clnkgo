<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::defaultMorphKeyType('uuid');
        Builder::defaultStringLength(191);

        if (app()->environment() !== 'local') {
	        URL::forceScheme('https');
        }
    }
}
