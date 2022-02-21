<?php

namespace App\Providers;

use App\Services\RepositoryService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('repository',RepositoryService::class);
    }
}
