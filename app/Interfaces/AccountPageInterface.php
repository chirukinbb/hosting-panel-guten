<?php

namespace App\Interfaces;

use Illuminate\Support\Facades\Request;

interface AccountPageInterface
{
    public function index();

    public function post(Request $request);
}
