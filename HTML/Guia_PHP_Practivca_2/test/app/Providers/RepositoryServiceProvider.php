<?php

namespace App\Providers;

use App\Repositories\Contracts\ProductoRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Pdo\PdoProductoRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProductoRepositoryInterface::class, PdoProductoRepository::class);
    }
}
