<?php

namespace App\Abstracts;

use App\Game\Card;
use App\Game\Collections\CardsCollection;
use App\Game\Collections\PlayersCollection;
use App\Game\Player;
use App\Game\Traits\CardTrait;
use App\Game\Traits\PlayerTrait;
use App\Game\Traits\RoundTrait;
use Illuminate\Support\Arr;

abstract class AbstractPokerTable
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

    public function __construct()
    {
        $this->minNominal = $this->getMinNominal();
        $this->playersCount = $this->getPlayersCount();
        $this->cardsInHand = $this->getCardsInHand();
        $this->blind = $this->getBlind();
        $this->places = $this->getPlaces();
        $this->cardDeck = $this->getCardDeck();
        $this->players = new PlayersCollection();
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    abstract public function getType();

    abstract protected function getMinNominal();

    abstract public function getCardsInHand();

    abstract public function getPlayersCount();

    abstract public function getBlind();

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

    public function setPlayer(int $userId, string $name, string|null $avatar): void
    {
        if ($this->players->count() <= $this->playersCount){
            $place = $this->getLandingPlace();
            $player = new Player($userId);
            $player->setName($name);
            if (!is_null($avatar)) $player->setAvatar($avatar);
            $player->setPlaceOnDesc($place);
            $this->players->push($player);

            if ($this->getCurrentPlayersCount() === $this->playersCount)
                $this->players->sortByPlaces();
        }
    }

    protected function getPlaces(): array
    {
        return range(0, $this->playersCount - 1);
    }

    protected function getLandingPlace()
    {
        $place = Arr::random($this->places);
        unset($this->places[$place]);

        return $place;
    }

    public function getPlayerWithStrongestHand(): array
    {
        return $this->round->getPlayerWithStrongestHand($this->players);
    }

    public function payBlinds()
    {
        $this->players->each(function (Player $player) {
            if ($player->isBB())
                $this->round->payBlind($player, $this->blind);

            if ($player->isLB())
                $this->round->payBlind($player, $this->blind / 2);
        });
    }

    public function getCurrentPlayersCount(): int
    {
        return $this->players->count();
    }

    public function eachPlayer(callable $func)
    {
        $this->players->each($func);
    }

    public function removePlayer(int $playerId)
    {
        $this->eachPlayer(function (Player $player) use ($playerId) {
            if ($player->getPlayerId() === $playerId) {
                $this->players->removeWhereObj($player);
                $this->places[$player->getPlace()] = $player->getPlace();
            }
        });
    }

    public function getChannelName(string $type): string
    {
        return $type.'-'.$this->id;
    }

    public function getTitle()
    {
        return $this->getType().', '.$this->playersCount.' Players, Blind '.$this->blind;
    }
}
