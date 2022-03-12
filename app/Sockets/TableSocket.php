<?php

namespace App\Sockets;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class TableSocket implements MessageComponentInterface
{
    protected \SplObjectStorage $players;

    public function __construct($playerIds)
    {
        $players = new \SplObjectStorage();

        foreach ($playerIds as $playerId) {
            $player = 5;
        }
    }

    function onOpen(ConnectionInterface $conn)
    {
        // TODO: Implement onOpen() method.
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
}
