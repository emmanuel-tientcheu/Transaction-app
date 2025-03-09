<?php

namespace App\User\Providers;

use App\User\Adapters\EloquantUserRepository;
use App\User\Adapters\InMemoryUserRepository;
use App\User\Ports\IUserRepository;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, EloquantUserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
