<?php

namespace App\Providers;

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
        //https://stackoverflow.com/questions/34378122/load-blade-assets-with-https-in-laravel
        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
    }
}
