<?php

namespace App\Http\Controllers;

use App\Game\Tests\Rules\FlushTest;
use App\Game\Tests\Rules\FourOfKindTest;
use App\Game\Tests\Rules\FullHouseTest;
use App\Game\Tests\Rules\HighCardTest;
use App\Game\Tests\Rules\OnePairTest;
use App\Game\Tests\Rules\RoyalStraightTest;
use App\Game\Tests\Rules\StraightFlushTest;
use App\Game\Tests\Rules\LowStraigthTest;
use App\Game\Tests\Rules\ThreeOfKind;
use App\Game\Tests\Rules\TwoPairsTest;
use App\Game\Tests\Winner\HighCard;

class HomeController extends Controller
{
    public function index()
    {
        $pocker = new HoldemNinePokerDeck(range(5,14));
        $pocker->setId(1);
        $pocker->startRound(1);
        //$pocker->changeStatuses(4);
        $pocker->payBlinds();
        $pocker->auction();
        //$pocker->changeStatuses(0);
        $pocker->preFlop();
        $pocker->flop();
        $pocker->turn();
        $pocker->river();
        $pocker->calculateHandValues();
        $pocker->getPlayerWithStrongestHand();
        dump($pocker);

        new RoyalStraightTest();echo '<br>';
        new StraightFlushTest();echo '<br>';
        new  FullHouseTest();echo '<br>';
        new FlushTest();echo '<br>';
        new LowStraigthTest();echo '<br>';
        new FourOfKindTest();echo '<br>';
        new ThreeOfKind();echo '<br>';
        new TwoPairsTest();echo '<br>';
        new OnePairTest();echo '<br>';
        new HighCardTest();echo '<br>';
new HighCard();

        //return view('home');
    }
}
