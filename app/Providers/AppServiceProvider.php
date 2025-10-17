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
            MonitorRepository::class,
        );

        $this->app->bind(
            \App\Repositories\Contracts\AlertContactRepositoryInterface::class,
            \App\Repositories\AlertContactRepository::class,
        );

        $this->app->bind(
            \App\Repositories\Contracts\StatusPageRepositoryInterface::class,
            \App\Repositories\StatusPageRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\MaintenanceWindowRepositoryInterface::class,
            \App\Repositories\MaintenanceWindowRepository::class
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
