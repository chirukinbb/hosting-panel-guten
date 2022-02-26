<?php

namespace App\Services;

use App\Models\UserSetting;

class SettingService
{
    private array $settings;

    public function __construct()
    {
        $this->settings = UserSetting::all();
    }

    public function get(string $name): string
    {
        return $this->settings[$name] ?? false;
    }

    public function url(string $name): string
    {
        return asset($this->get($name));
    }
}
