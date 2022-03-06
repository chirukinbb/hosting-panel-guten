<?php

namespace App\Game;

use App\Abstracts\AbstractCombo;
use App\Game\Collections\CardsCollection;
use App\Repositories\Admin\UserDataRepository;

class Player
{
    protected CardsCollection $cards;
    protected int $place;
    protected UserCombo $combo;
    protected bool $inGame = true;
    protected bool $inRound = true;
    protected bool $isDealer = false;
    protected bool $isBB = false;
    protected bool $isLB = false;
    //protected UserDataRepository $repository;

    public function __construct(protected int $playerId)
    {
        $this->cards = new CardsCollection();
        //$this->repository = new UserDataRepository();
    }

    public function setPlaceOnDesc(int $place)
    {
        $this->place = $place;
    }

    public function giveCard(Card $card)
    {
        $this->cards->push($card);
    }

    public function setDealerStatus(bool $isDealer)
    {
        $this->isDealer =  $isDealer;
    }

    public function setBBStatus(bool $isBB)
    {
        $this->isBB =  $isBB;
    }

    public function setLBStatus(bool $isLB)
    {
        $this->isLB =  $isLB;
    }

    public function getCards()
    {
        return $this->cards->getCollection();
    }

    public function getPlace(): int
    {
        return $this->place;
    }
}
