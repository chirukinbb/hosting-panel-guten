<?php

namespace App\Http\Controllers;

use App\Game\Tests\Rules\FlushTest;
use App\Game\Tests\Rules\FourOfKindTest;
use App\Game\Tests\Rules\FullHouseTest;
use App\Game\Tests\Rules\HighCardTest;
use App\Game\Tests\Rules\OnePairTest;
use App\Game\Tests\Rules\StraightFlushTest;
use App\Game\Tests\Rules\StraigthTest;
use App\Game\Tests\Rules\ThreeOfKind;
use App\Game\Tests\Rules\TwoPairsTest;
use App\Game\Tests\Winner\HighCard;

class HomeController extends Controller
{
    public function index()
    {
//        $pocker = new HoldemNinePokerDeck(range(5,14));
//        $pocker->setId(1);
//        $pocker->startRound(1);
//        $pocker->changeStatuses(8);
//        $pocker->changeStatuses(0);
//        $pocker->preFlop();
//        $pocker->flop();
//        $pocker->turn();
//        $pocker->river();
//        $pocker->calculateHandValues();
//        $pocker->getPlayerWithStrongestHand();
//        dump($pocker);
//
//        new StraightFlushTest();echo '<br>';
//        new  FullHouseTest();echo '<br>';
//        new FlushTest();echo '<br>';
//        new StraigthTest();echo '<br>';
//        new FourOfKindTest();echo '<br>';
//        new ThreeOfKind();echo '<br>';
//        new TwoPairsTest();echo '<br>';
//        new OnePairTest();echo '<br>';
//        new HighCardTest();echo '<br>';
new HighCard();

        //return view('home');
    }
}
