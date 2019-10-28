<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Video;
use App\Observers\VideoObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('SubtitlesExtractingService', SubtitlesExtractingService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Video::observe(VideoObserver::class);
    }
}
