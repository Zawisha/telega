<?php

namespace App\Providers;

use App\Models\NotReadyResults;
use App\Models\OneClientSettingsGroupsTelegramLine;
use App\Models\OneClientTelegramLine;
use App\Services\VkService;
use Illuminate\Support\ServiceProvider;

class VkProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(VkService::class, function($app){
            return new VkService(new OneClientSettingsGroupsTelegramLine(), new NotReadyResults());
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
