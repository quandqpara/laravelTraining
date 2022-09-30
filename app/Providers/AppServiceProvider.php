<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            \App\Repositories\Teams\TeamsRepositoryInterface::class,
            \App\Repositories\Teams\TeamsRepository::class,
        );
        $this->app->singleton(
            \App\Repositories\Employees\EmployeesRepositoryInterface::class,
            \App\Repositories\Employees\EmloyeesRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
