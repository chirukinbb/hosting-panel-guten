<?php

namespace App\Http\Controllers;

use App\Game\Tables\HoldemTwoPokerTable;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $a=[2,1,5];
sort($a);
dd($a);
        return view('home');
    }
}
