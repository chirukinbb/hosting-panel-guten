<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string get(string $name)
 * @method static string url(string $name)
 */
class Setting extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'setting';
    }
}
