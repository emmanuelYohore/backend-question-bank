<?php

namespace App\Providers;

use App\Repositories\Eloquent\BankItemRepository;
use App\Repositories\Eloquent\EnqueteBankRepository;
use App\Repositories\Eloquent\EnqueteRepository;
use App\Repositories\Eloquent\FormatReponseRepository;
use App\Repositories\Eloquent\ItemRepository;
use App\Repositories\Eloquent\ModaliteReponseRepository;
use App\Repositories\Eloquent\RepondantRepository;
use App\Repositories\Eloquent\ReponseRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\BankItemItemRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Interfaces\BankItemRepositoryInterface;
use App\Repositories\Interfaces\EnqueteBankRepositoryInterface;
use App\Repositories\Interfaces\EnqueteRepositoryInterface;
use App\Repositories\Interfaces\FormatReponseRepositoryInterface;
use App\Repositories\Interfaces\ItemRepositoryInterface;
use App\Repositories\Interfaces\ModaliteReponseRepositoryInterface;
use App\Repositories\Interfaces\RepondantRepositoryInterface;
use App\Repositories\Interfaces\ReponseRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\BankItemItemRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
   
    public function register(): void
    {
      $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
      $this->app->bind(EnqueteRepositoryInterface::class, EnqueteRepository::class);
      $this->app->bind(BankItemRepositoryInterface::class, BankItemRepository::class);
      $this->app->bind(FormatReponseRepositoryInterface::class, FormatReponseRepository::class);
      $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
      $this->app->bind(ModaliteReponseRepositoryInterface::class, ModaliteReponseRepository::class);
      $this->app->bind(ReponseRepositoryInterface::class, ReponseRepository::class);
      $this->app->bind(RepondantRepositoryInterface::class, RepondantRepository::class);
      $this->app->bind(EnqueteBankRepositoryInterface::class, EnqueteBankRepository::class);
      $this->app->bind(BankItemItemRepositoryInterface::class, BankItemItemRepository::class);
      $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);

    }

    public function boot(): void
    {
        
    }
}
