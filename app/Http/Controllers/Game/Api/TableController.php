<?php

namespace App\Http\Controllers\Game\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function action(Request $request)
    {

    }

    public function leave(Request $request)
    {
        return json_encode(['screen'=>'list']);
    }
}
