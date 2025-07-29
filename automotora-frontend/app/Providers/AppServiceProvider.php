<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->environment('local', 'development')) {
            URL::forceRootUrl(config('app.url'));
        }
    }
}