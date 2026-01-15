<?php

namespace App\Providers;

use App\Repositories\Eloquent\EnqueteRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\EnqueteRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
   
    public function register(): void
    {
      $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
      $this->app->bind(EnqueteRepositoryInterface::class, EnqueteRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
