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

    const ROUND_START_STEP = 0;
    const ROUND_PREFLOP_STEP = 1;
    const ROUND_FLOP_STEP = 2;
    const ROUND_TURN_STEP = 3;
    const ROUND_RIVER_STEP = 4;
    const ROUND_SHOWDOWN_STEP = 5;
    const ROUND_PAYMENT_STEP = 6;

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

    public function setActivePlayersCountOnEndRound()
    {
        $this->round->setCountPlayersEnd(
            $this->players->getActivePlayersCount()
        );
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
        return (isset($this->round) && $this->round->getAnte()) ?
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

    public function removePlayersCombos()
    {
        $this->players->each(function (Player $player) {
            $player->removeCombos();
        });
    }

    /**
     * установить игрока, кто будет сейчас  делать ставку
     */
    public function setNextPlayerAuction()
    {
        $prevPlayerAuctionPlayerPlace = $this->round->getLastAuctionPlayerPlace();
        $this->players->sortFromPlace($prevPlayerAuctionPlayerPlace);

        $newAuctioneer = false;

        $this->players->each(function (Player $player) use ($prevPlayerAuctionPlayerPlace, &$newAuctioneer) {
            if ($newAuctioneer && $player->isInRound()) {
                $this->round->setLastAuctionPlayerPlace($player->getPlace());
                $newAuctioneer = false;

                $player->eachAction(function (AbstractGameAction $action) use ($player) {
                    switch ($action->getId()) {
                        case 1:
                            $action->setIsActive($player->getBid() === $this->round->getMaxBid());
                            break;
                        case 2:
                            $action->setIsActive($player->getAmount() > $this->blind);
                            break;
                        case 3:
                            $action->setIsActive($player->getAmount() > 0);
                            break;
                        case 4:
                            $action->setIsActive($player->getBid() < $this->round->getMaxBid());
                            break;
                        default:
                            $action->setIsActive(true);
                    }
                });
            }

            if ($prevPlayerAuctionPlayerPlace === $player->getPlace()) {
                $this->players->sortFromPlace($player->getPlace());

                $player->eachAction(function (AbstractGameAction $action) {
                    $action->setIsActive(false);
                });

                $newAuctioneer = true;
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
            $player->annulledBid();
            $i = 0;

            do {
                // собственно, переливка фишек от ставок игрока в банк по границам
                $amount = ($bidBorders[$i] > $fullBid) ? $fullBid : $bidBorders[$i];
                $this->round->setBids($absBorders[$i], $amount);
                $fullBid -= $amount;
                $i ++;
            } while ($fullBid > 0);
        });

        $this->round->normalizeBank();
    }

    /**
     * выплачиваем награды
     */
    public function payToWinners()
    {
        $playersByCombo = $this->players->getByHandPower();

        do {
            // отбор игроков с найвысшей комбой
            $checkedCombo = array_shift($playersByCombo);
            ksort($checkedCombo);

            do{
                /**
                 * @var Player $player
                 */
                // игрок с меньшей ставкой
                $player = $checkedCombo[0];
                $bankInBorder = $this->round->getBankValueByAbsBorder($border = $player->getBid());
                $player->setWinner(true);
                /**
                 * выплата приза с велъю банка по текущей границе
                 */
                $amountToPayment = $bankInBorder / count($checkedCombo);
                foreach ($checkedCombo as $player) {
                    $player->addAmount($amountToPayment);
                }

                $this->round->annulledAmount($border);
                array_shift($checkedCombo);
            }while($checkedCombo);
        } while ($this->round->getFullBank() > 0);
    }

    /**
     * передать ли ход следующему игроку?
     *
     * @return bool
     */
    public function isTurnTransfer(): bool
    {
        $currentPlayerPlace  = $this->getLastAuctionPlayerPlace();
        $nextPlayer = $this->players->getNextActivePlayer($currentPlayerPlace);
        $currentPlayer =  $this->players->get($currentPlayerPlace);
        $isBidEq = ($currentPlayer->getBid() === $this->round->getMaxBid());
        $isNextPlayerLastRaise = ($nextPlayer->getPlace() === $this->round->getLastRaisePlayerPlace());
        $isCurrentPlayerLastRaise = ($currentPlayer->getPlace() === $this->round->getLastRaisePlayerPlace());

        return ($isCurrentPlayerLastRaise || ($isBidEq && $isNextPlayerLastRaise));
    }

    /**
     * завершить стол?
     *
     * @return bool
     */
    public function isTableFinish(): bool
    {
        return $this->getCurrentPlayersCount() === 1;
    }

    /**
     * открывать ли карты сейчас?
     *
     * @return bool
     */
    public function isShowDown(): bool
    {
        if ($this->round->getCurrentStep() < 4)
            return $this->players->hasOnlyAllInPlayers();

        return true;
    }

    /**
     * собирать ли ставки игроков в банк?
     *
     * @return bool
     */
    public function isExtractBidsToBank():bool
    {
        return $this->players->isNextPlayerLastRaise(
            $this->round->getLastAuctionPlayerPlace(),
            $this->round->getLastRaisePlayerPlace()
        ) && !$this->players->isOnlyOneActivePlayerInRound();
    }

    /**
     * окончить ли раунд без вскрытия карт?
     *
     * @return bool
     */
    public function isNewRoundWithoutShowdown(): bool
    {
        return $this->players->isOnlyOneActivePlayerInRound();
    }

    /**
     * получить индекс места игрока за столом, делающего сейчас ставку
     *
     * @return int
     */
    public function getLastAuctionPlayerPlace()
    {
        return $this->round->getLastAuctionPlayerPlace();
    }

    public function getBank()
    {
        return isset($this->round) ? $this->round->getBankCollection() : [0];
    }

    public function showdownPlayerActions(): void
    {
        $this->players->showdownPlayerActions($this->round->getLastRaiseUserPlace());
    }

    public function getCurrentBid()
    {
        return $this->round->getMaxBid();
    }

    public function isShowdownAction(): bool
    {
        return !$this->players->hasOnlyAllInPlayers() || $this->round->getCurrentStep() === 3;
    }

    public function existsLosers(): bool
    {
        return $this->players->existsLosers();
    }

    public function getActivePlayersCount():int
    {
        return $this->players->getActivePlayersCount();
    }

    public function getPlaceInTable()
    {
        return $this->round->getPlayerPlace();
    }

    public function getRatingByTable()
    {
        return $this->gerRatingByPlace($this->avgPlace());
    }

    public function avgPlace()
    {
        return  array_sum(range($this->round->getCountPlayersStart(),$this->round->getCountPlayersEnd() + 1)) / ($this->round->getCountPlayersStart() - $this->round->getCountPlayersEnd());
    }

    public function gerRatingByPlace(int $placeInGame): float|int
    {
        return  floor(100 -  100 * ($this->getPlayersCount() - $placeInGame) / $this->getPlayersCount());
    }

    public function eachLooser(callable $func)
    {
        $this->round->eachLooser($func);
    }

    public function addLostPlayer(Player $player)
    {
        $this->round->addLostPlayer($player);
    }

    public function isNextPlayerShowdownAction()
    {
        return $this->players->isNextPlayerShowdownAction();
    }

    public function setShowdownAction(bool $action)
    {
        $this->players->setShowdownAction($action);
    }

    public function getTimerForPlayer(Player $player): int
    {
        return isset($this->round) ?
            (($this->round->getLastAuctionPlayerPlace() === $player->getPlace()) ?
            ($this->getCurrentStepInRound() > 4 ? $this->getTimeOnTurn() : 5) : 0)
        : 0;
    }

    public function result(Player $player)
    {
        return isset($this->round) ?
            ($this->round->getLastAuctionPlayerPlace() === $player->getPlace() ?
            $player->getLastActionId() : null)
            : null;
    }

    public function timer(Player $player)
    {
        return $player->isCurrentShowdown() ?
            ($this->round->getLastAuctionPlayerPlace() ? $this->getTimeOnTurn() : 0) : 0;
    }
}
