<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        Paginator::useBootstrapFive();

        // 💡 CORREÇÃO: Verifica se NÃO estamos no console E se a requisição é HTTPS
        if (! $this->app->runningInConsole() && Request::server('HTTP_X_FORWARDED_PROTO') === 'https') {
             URL::forceScheme('https');
        }
    }
    
}
