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

abstract class AbstractPokerTable
{
    use RoundTrait, CardTrait, PlayerTrait;

    protected int $blind;
    protected int $playersCount;
    protected int $minNominal;
    protected int $cardsInHand;
    protected array $places;
    protected PlayersCollection $players;
    protected CardsCollection $cardDeck;
    protected Round $round;
    protected int $timeOnTurn;

    public function __construct(protected int $id)
    {
        $this->minNominal = $this->getMinNominal();
        $this->playersCount = $this->getPlayersCount();
        $this->cardsInHand = $this->getCardsInHand();
        $this->blind = $this->getBlind();
        $this->places = $this->getPlaces();
        $this->players = new PlayersCollection();
        $this->cardDeck = $this->getCardDeck();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getTimeOnTurn(): int
    {
        return $this->timeOnTurn;
    }

    abstract public function getType();

    abstract protected function getMinNominal();

    abstract public function getCardsInHand();

    abstract public function getPlayersCount();

    abstract public function getBlind();

    protected function getCardDeck()
    {
        $cards = new CardsCollection();
        $deckNamesPool = config('poker.card.names');
        $deckSuitsPool = config('poker.card.suits');
        $id = 0;

        foreach ($deckNamesPool as $nominalIndex => $nominal) {
            foreach ($deckSuitsPool as $suitIndex => $suit) {
                $cards->push(new Card($nominalIndex, $suitIndex, $id, $nominal, $suit));
                $id++;
            }
        }

        return $cards->removeFirsts($this->minNominal * count($deckSuitsPool));
    }

    public function setPlayer(int $userId, string $name, string $avatar): int
    {
        $place = -1;

        if ($this->players->count() <= $this->playersCount) {
            $place = $this->getLandingPlace();
            $player = new Player($userId);
            $player->setName($name);
            $player->setAvatar($avatar);
            $player->setPlaceOnDesc($place);
            $this->players->push($player);

            if ($this->getCurrentPlayersCount() === $this->playersCount)
                $this->players->sortByPlaces();
        }

        return $place;
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
            if ($player->getUserId() === $playerId) {
                $this->players->removeWhereObj($player);
                $this->places[$player->getPlace()] = $player->getPlace();
            }
        });
    }

    public function getChannelName(string $type): string
    {
        return $type . '.' . $this->id;
    }

    public function getTitle()
    {
        return $this->round->getAnte() ?
            $this->getType() . ', ' . $this->playersCount . ' Players, Blind ' . $this->blind . ', Ante ' . $this->round->getAnte() :
            $this->getType() . ', ' . $this->playersCount . ' Players, Blind ' . $this->blind;
    }

    public function getDealerIndex(): int
    {
        return $this->players->getDealerIndex();
    }

    public function removePlayersCards()
    {
        $this->players->each(function (Player $player) {
            $player->removeCards();
        });
    }

    /**
     * установить игрока, кто будет сейчас  делать ставку
     */
    public function setNextPlayerAuction()
    {
        $prevPlayerAuctionUserId = $this->round->getLastAuctionUserId();
        $newAuctioneer = false;

        $this->players->each(function (Player $player) use ($prevPlayerAuctionUserId, &$newAuctioneer) {
            if ($prevPlayerAuctionUserId === $player->getUserId()) {
                $this->players->sortFromPlace($player->getPlace());

                $player->eachAction(function (AbstractGameAction $action) {
                    $action->setIsActive(false);
                });

                $newAuctioneer = true;
            }

            if ($newAuctioneer) {
                $this->round->setLastAuctionUserId($player->getUserId());
                $newAuctioneer = false;

                $player->eachAction(function (AbstractGameAction $action) use ($player) {
                    switch ($action->getId()) {
                        case 1:
                            $action->setIsActive($player->getBid() === $this->round->getMaxBidInTurn());
                            break;
                        case 2:
                            $action->setIsActive($player->getAmount() > $this->blind);
                            break;
                        case 3:
                            $action->setIsActive($player->getAmount() > 0);
                            break;
                        case 4:
                            $action->setIsActive($player->getBid() < $this->round->getMaxBidInTurn());
                            break;
                        default:
                            $action->setIsActive(true);
                    }
                });
            }
        });
    }

    /**
     * сливаем все ставки в банк
     */
    public function bidsToBank()
    {
        $absBorders = $this->players->calculateBidBordersAbsolute();
        $bidBorders = $this->players->calculateBidBordersRelative($absBorders);

        $this->players->each(function (Player $player) use ($absBorders,$bidBorders) {
            $fullBid = $player->getBid();
            $i = 0;

            do {
                // собственно, переливка фишек от ставок игрока в банк по границам
                $amount = ($bidBorders[$i] > $fullBid) ? $fullBid : $bidBorders[$i];
                $this->round->setBids($absBorders[$i], $amount);
                $fullBid -= $amount;
            } while ($fullBid > 0);
        });
    }

    /**
     * выплачиваем награды
     */
    public function payToWinners()
    {
        $playersByCombo = $this->players->getByHandPower();
        $absBorders = $this->players->calculateBidBordersAbsolute();

        do {
            // отбор игроков с найвысшей комбой
            $checkedCombo = array_shift($playersByCombo);
            ksort($checkedCombo);

            do{
                $count = count($checkedCombo);
                /**
                 * @var Player $player
                 */
                // игрок с меньшей ставкой
                $player = array_shift($checkedCombo);
                $index = array_search($player->getBid(),$this->round->getFullBank());

            }while($checkedCombo);

            // находжение игрока, внесшего найменьше фишек
            $minBid = 10000000000000;
            foreach ($checkedCombo as $player) {
                /**
                 * @var Player $player
                 */
                if ($minBid > $player->getBid()) {
                    $minBid = $player->getBid();
                    $minBidPlayer = $player;
                }
            }
            // обнуление части банка с этой ставкой
            $i = 0;

            do {
                $bankInBorder = $this->round->getPartBank($i);
                $remainder = $minBid - $bidBorders;
                $this->round->annulledAmount($i);
                // зачет игроку призовой суммы с этой части банка
                foreach ($checkedCombo as $player) {
                    /**
                     * @var Player $player
                     */
                    $player->addAmount($bankInBorder  / count($checkedCombo));
                }
            }while($remainder);
        } while ($this->round->getFullBank() > 0);
    }

    /**
     * получить ID игрока, делающего сейчас ставку
     *
     * @return int
     */
    public function getAuctionUserId()
    {
        return $this->round->getLastAuctionUserId();
    }
}
