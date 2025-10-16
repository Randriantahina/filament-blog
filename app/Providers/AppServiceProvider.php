<?php

namespace App\Providers;

use App\Repositories\Contracts\MonitorRepositoryInterface;
use App\Repositories\MonitorRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            MonitorRepositoryInterface::class,
            MonitorRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}