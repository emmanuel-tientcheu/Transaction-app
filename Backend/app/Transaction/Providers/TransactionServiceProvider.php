<?php

namespace App\Transaction\Providers;

use App\Transaction\Adapters\EloquantTransactionRepository;
use App\Transaction\Ports\ITransactionRepository;
use Illuminate\Support\ServiceProvider;

class TransactionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ITransactionRepository::class, EloquantTransactionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
