<?php

namespace App\Providers;

use App\Services\SettingService;
use Closure;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('setting',SettingService::class);
    }
}
