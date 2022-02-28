<?php

namespace App\Sockets;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class AppSocket  implements MessageComponentInterface
{
    public function __construct(\SplObjectStorage $storage = null)
    {
    }

    function onOpen(ConnectionInterface $conn)
    {
        $conn->send('hello');
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
