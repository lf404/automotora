<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // ¡MUY IMPORTANTE! Importamos la fachada URL.

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // La solución forzosa:
        // Le ordenamos a Laravel que use la URL de nuestro archivo .env como la raíz
        // para TODAS las rutas que genere.
        // La condición if() asegura que esto solo pase en desarrollo, no en producción.
        if ($this->app->environment('local', 'development')) {
            URL::forceRootUrl(config('app.url'));
        }
    }
}