<?php

namespace App\Sockets;

use App\Repositories\Admin\UserRepository;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class AppSocket  implements MessageComponentInterface
{
    public function __construct(
        protected UserRepository    $userRepository,
        protected \SplObjectStorage $storage
    ) {}

    function onOpen(ConnectionInterface $conn)
    {
        $this->storage->attach($conn);
    }

    function onClose(ConnectionInterface $conn)
    {
        $this->storage->detach($conn);
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        // TODO: Implement onError() method.
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
        $msg = json_decode($msg);

        if (method_exists($this, $msg->method)) {
            $response = call_user_func([$this, $msg->method], $msg->data);
            $from->send(json_encode($response));
        }
    }

    protected function signin($data)
    {
        if ($this->userRepository->exists($data->email))
            return ['attention'=>'Account with this email exists!'];

        $this->userRepository->create((array)$data);

        return ['success'=>'New Account created! Check email!'];
    }

    protected function login($data)
    {
        $token = $this->userRepository->authToken((array)$data);

        return $token ?
            ['success'=>['token'=>$token]] :
            ['error'=>'Oops! What`s happened. Try latter or another credentials'];
    }

    protected function find()
    {}

    protected function history()
    {}
}
