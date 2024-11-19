<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\EncryptionService;
use App\Services\DiffieHellmanService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Register EncryptionService
        $this->app->singleton(EncryptionService::class, function ($app) {
            return new EncryptionService();
        });

        // Register DiffieHellmanService
        $this->app->singleton(DiffieHellmanService::class, function ($app) {
            return new DiffieHellmanService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
