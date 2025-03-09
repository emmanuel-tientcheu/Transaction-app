<?php

namespace App\Providers;

use App\Core\Adapters\RandomIdGenerator;
use App\Core\Ports\IDgenerator;
use App\Core\Services\Hasher\Hasher;
use App\Core\Services\Hasher\IHasher;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IDgenerator::class, RandomIdGenerator::class);
        $this->app->bind(IHasher::class, Hasher::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
