<?php

namespace App\Providers;

use App\Models\NotReadyResults;
use App\Models\OneClientSettingsFiltersTelegramLine;
use App\Models\OneClientSettingsGroupsTelegramLine;
use App\Models\OneClientTelegramLine;
use App\Models\ReadyResults;
use App\Models\SearchTelegramLine;
use App\Services\SearchService;
use Illuminate\Support\ServiceProvider;

class SearchProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SearchService::class, function($app){
            return new SearchService(
                new SearchTelegramLine(),
                new OneClientTelegramLine(),
                new OneClientSettingsGroupsTelegramLine(),
                new OneClientSettingsFiltersTelegramLine(),
                new NotReadyResults(),
                new ReadyResults()
            );
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
