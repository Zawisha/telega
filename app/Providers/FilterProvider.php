<?php

namespace App\Providers;

use App\Models\LinkInPostFilters;
use App\Models\StoplinksVK;
use App\Models\StopSlovaFilters;
use App\Models\VseAvtoryChataFilters;
use App\Services\FilterService;
use Illuminate\Support\ServiceProvider;

class FilterProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(FilterService::class, function($app){
            return new FilterService(new VseAvtoryChataFilters(), new StopSlovaFilters(), new LinkInPostFilters(), new StoplinksVK());
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
