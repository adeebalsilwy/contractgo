<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ItemPricingIntegrationService;

class ItemPricingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ItemPricingIntegrationService::class, function ($app) {
            return new ItemPricingIntegrationService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}