<?php

namespace App\Http\Controllers\Game\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;

class UserController extends Controller
{
    public function profile()
    {
        return ProfileResource::make(\Auth::user());
    }
}
