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
//        $table = new HoldemTwoPokerTable();
//        $table->setPlayer(4);
//        $table->setPlayer(45);
//$table->removePlayer(4);
//        dd($table);
        return view('home');
    }
}
