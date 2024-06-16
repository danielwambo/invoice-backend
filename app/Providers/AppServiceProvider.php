<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MpesaService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register the MpesaService as a singleton
        $this->app->singleton(MpesaService::class, function ($app) {
            return new MpesaService();
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
