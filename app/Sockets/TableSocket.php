<?php

namespace App\Sockets;

use App\Abstracts\AbstractPokerDeck;
use App\Models\Game\Table;
use App\Repositories\Admin\UserRepository;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use React\EventLoop\LoopInterface;
use App\Game\Tables\HoldemTwoPokerDeck;

class TableSocket implements MessageComponentInterface
{
    protected AbstractPokerDeck $table;
    protected int $turnOf = -1;
    const THINK_INTERVAL = 20;
    const BETWEEN_ROUND_INTERVAL = 1;

    public function __construct(
        protected string $tableClass,
        protected UserRepository $userRepository,
        protected PlayersCollection $playersCollection,
        protected int $port,
        protected LoopInterface $loop
    ) {}

    function onOpen(ConnectionInterface $conn)
    {
        if ($this->tableClass::$count >= $this->playersCollection->count()) {
            $this->playersCollection->push($conn);
            $this->playersCollection->each(function (ConnectionInterface $connection) {
                $connection->send(json_encode([
                    'action'=>'attention',
                    'message'=>'You was connect to table, wait other players',
                    'count'=>$this->playersCollection->count()
                ]));
            });
        }

        if ($this->tableClass::$count === $this->playersCollection->count()) {
            $this->table = new $this->tableClass($this->playersCollection);
            $table  = Table::create(['table_class'=>$this->tableClass,'port'=>$this->port]);
            $this->table->setId($table->id);
            $this->newRound(1);
        }
    }

    function onClose(ConnectionInterface $conn)
    {
        // TODO: Implement onClose() method.
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        // TODO: Implement onError() method.
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
        // TODO: Implement onMessage() method.
    }

    protected function newRound(int $number)
    {
        Table::update(
            ['id'=>$this->table->getId()],
            ['round_no'=>$number]
        );
        $this->table->startRound($number);
        $this->table->changeStatuses($number % $this->table->getPlayersCount());
        $this->table->preFlop();
        $this->table->payBlinds();

        $this->turnOf = ($number % $this->table->getPlayersCount()) + 3;

        $this->loop->addTimer(self::THINK_INTERVAL,function () {
            $index = $this->turnOf;
            $this->turnOf = -1;

            $this->loop->addTimer(self::BETWEEN_ROUND_INTERVAL,function () use ($index) {
                $index ++;
                $this->turnOf = $index;
            });
        });
    }
}
