<?php

namespace App\Game;

use App\Abstracts\AbstractCombo;
use App\Repositories\Admin\UserDataRepository;

class Player
{
    /**
     * @param Card[]
     */
    protected array $cards;
    protected AbstractCombo $combo;
    protected bool $inGame;
    protected bool $inRound;
    protected bool $isDealer;
    //protected UserDataRepository $repository;

    public function __construct(protected int $playerId)
    {
        //$this->repository = new UserDataRepository();
    }

    public function giveCard(int $index, Card $card)
    {
        $this->cards[$index] = $card;
    }

    public function setDealerStatus(bool $isDealer)
    {
        $this->isDealer =  $isDealer;
    }

    public function getCards()
    {
        return $this->cards;
    }
}
