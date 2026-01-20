<?php

namespace App\Providers;

use App\Repositories\Eloquent\BankItemRepository;
use App\Repositories\Eloquent\EnqueteRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\BankItemRepositoryInterface;
use App\Repositories\Interfaces\EnqueteRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
   
    public function register(): void
    {
      $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
      $this->app->bind(EnqueteRepositoryInterface::class, EnqueteRepository::class);
      $this->app->bind(BankItemRepositoryInterface::class, BankItemRepository::class);
    }

    public function boot(): void
    {
        
    }
}
