<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ExtraCurrency\ExtraCurrency;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('extra_currency', function ($app) {
            return new ExtraCurrency;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
