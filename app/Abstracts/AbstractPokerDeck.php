<?php

namespace App\Abstracts;

use App\Game\Card;
use App\Game\Collections\CardsCollection;
use App\Game\Collections\PlayersCollection;
use App\Game\Player;
use App\Game\Round;
use App\Game\Traits\CardTrait;
use App\Game\Traits\PlayerTrait;
use App\Game\Traits\RoundTrait;
use Illuminate\Support\Arr;

abstract class AbstractPokerDeck
{
    use RoundTrait,CardTrait,PlayerTrait;

    protected int $id;
    protected int $blind;
    protected int $playersCount;
    protected int $minNominal;
    protected int $cardsInHand;
    protected array $places;
    protected PlayersCollection $players;
    protected CardsCollection $cardDeck;

    public function __construct(protected array $userIds)
    {
        $this->minNominal = $this->getMinNominal();
        $this->playersCount = $this->getPlayersCount();
        $this->cardsInHand = $this->getCardsInHand();
        $this->blind = $this->getBlind();
        $this->places = $this->getPlaces();
        $this->players = $this->getPlayers();
        $this->cardDeck = $this->getCardDeck();
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    abstract protected function getMinNominal();

    abstract protected function getCardsInHand();

    abstract protected function getPlayersCount();

    abstract protected function getBlind();

    protected function getCardDeck()
    {
        $cards  = new CardsCollection();
        $deckNamesPool = config('poker.card.names');
        $deckSuitsPool = config('poker.card.suits');
        $id = 0;

        foreach ($deckNamesPool as $nominalIndex => $nominal) {
            foreach ($deckSuitsPool as $suitIndex => $suit) {
                $cards->push(new Card($nominalIndex,$suitIndex,$id,$nominal,$suit));
                $id++;
            }
        }

        return $cards->removeFirsts($this->minNominal*count($deckSuitsPool));
    }

    protected function getPlayers()
    {
        $players = new PlayersCollection();

        for ($i = 0; $i < $this->playersCount; $i ++){
            $place = $this->getLandingPlace();
            $player = new Player($this->userIds[$i]);
            $player->setPlaceOnDesc($place);
            $player->setDealerStatus($place === 0);
            $player->setLBStatus($place === 1);
            $player->setBBStatus($place === 3);
            $players->push($player);
        }

        return $players->sortByPlaces();
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

    /**
     * @return Round
     */
    public function getRound(): Round
    {
        return $this->round;
    }
}