<?php


namespace App\Providers;

use App\UnitOfWorkRepository;
use Illuminate\Support\ServiceProvider as AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider
{
    public function register()
    {
        $this->app->singleton(UnitOfWorkRepository::class);
    }
}