<?php

namespace App\Providers;

use App\Repositories\MongoPersonRepository;
use App\Repositories\PersonRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PersonRepositoryInterface::class, MongoPersonRepository::class);
    }
}
