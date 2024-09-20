<?php

namespace App\Providers;

use App\Models\OneClientSettingsGroupsTelegramLine;
use App\Models\SearchErrors;
use App\Models\TelegramInviteUsers;
use App\Models\TelegramUsers;
use App\Services\TelegramService;
use Illuminate\Support\ServiceProvider;

class TelegramProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TelegramService::class, function($app){
            return new TelegramService(new TelegramInviteUsers(),new TelegramUsers(),new OneClientSettingsGroupsTelegramLine(), new SearchErrors());
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
