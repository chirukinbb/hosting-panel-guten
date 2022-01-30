<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface AccountPageInterface
{
    public function index();

    public function post(array $request);
}
