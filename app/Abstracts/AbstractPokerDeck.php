<?php

namespace App\Abstracts;

use App\Game\Card;
use App\Game\Player;
use App\Game\Round;
use Illuminate\Support\Arr;

abstract class AbstractPokerDeck
{
    protected int $id;
    protected int $playersCount;
    protected int $minNominal;
    protected int $cardsInHand;
    /**
     * @param Player[]
     */
    protected array $players;
    /**
     * @param Card[]
     */
    protected array $cardDeck;
    protected Round $round;

    /**
     * @param int[]
     */
    protected array $places;

    public function __construct(protected array $userIds)
    {
        $this->minNominal = $this->getMinNominal();
        $this->playersCount = $this->getPlayersCount();
        $this->cardsInHand = $this->getCardsInHand();
        $this->places = $this->getPlaces();
        $this->players = $this->getPlayers();
        $this->cardDeck = $this->getCardDeck();
        $this->round  = new Round($this->cardDeck);
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function startRound(int $number)
    {
        $this->round->setNumber($number);
        $this->round->preFlop($this->players,$this->cardsInHand);
    }

    abstract protected function getMinNominal();

    abstract protected function getCardsInHand();

    abstract protected function getPlayersCount();

    protected function getCardDeck()
    {
        $cardsPool = [];
        $deckNamesPool = config('poker.card.names');
        $deckSuitsPool = config('poker.card.suits');

        foreach ($deckNamesPool as $index => $name) {
            foreach ($deckSuitsPool as $key => $suit) {
                $cardsPool[] = new Card($name.' '.$suit,$index.$key);
            }
        }

        return array_slice($cardsPool,$this->minNominal * count($deckSuitsPool));
    }

    protected function getPlayers()
    {
        $players = [];

        for ($i = 0; $i < $this->playersCount; $i ++){
            $place = $this->getLandingPlace();
            $player = new Player($this->userIds[$i]);
            $player->setDealerStatus($i === 0);
            $players[$place] = $player;
        }

        ksort($players);

        return $players;
    }

    protected function getPlaces()
    {
        return range(0, $this->playersCount - 1);
    }

    protected function getLandingPlace()
    {
        $place = Arr::random($this->places);
        unset($this->places[$place]);

        return $place;
    }
}
