<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static get(string $name)
 * @method static url(string $name)
 */
class Setting extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'setting';
    }
}
