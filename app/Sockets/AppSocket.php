<?php

namespace App\Sockets;

use App\Game\Tables\HoldemTwoPokerDeck;
use App\Repositories\Admin\UserRepository;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class AppSocket  implements MessageComponentInterface
{
    protected array $tables = [
        HoldemTwoPokerDeck::class
    ];

    protected PlayersCollection $turn;

    public function __construct(
        protected UserRepository    $userRepository,
        protected \SplObjectStorage $storage
    ) {
        $this->turn   = new PlayersCollection();
    }

    function onOpen(ConnectionInterface $conn)
    {
        $this->storage->attach($conn);
    }

    function onClose(ConnectionInterface $conn)
    {
        $this->turn->each(function (PlayersCollection $collection) use ($conn){
            $collection->removeWhereObj($conn);
        });
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
            $response = call_user_func([$this, $msg->method], $msg->data, $from);

            if (is_array($response))
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

    protected function find($data, ConnectionInterface $conn)
    {
//        Player::create([
//            'user_id'=>$this->userRepository->whereApiToken($data->token),
//            'table_class'=>$this->tables[$data->tableType]
//        ]);
        $this->turn->setType($data->tableType);
        $this->turn->push($conn);

        if ($this->turn->get($data->tableType)->count() < $this->tables[$data->tableType]::$count)
            return ['attention'=>'Please wait, searching opponents...'];

        \Artisan::call('PokerDeck:start',[
            '--port'=>$port = $this->getFreePort(),
            '--table'=>$this->tables[$data->tableType]
        ]);

        $this->turn->get($data->tableType)->each(function (ConnectionInterface $connection) use ($port){
            $connection->send(json_encode([
                'action'=>'connect',
                'port'=>$port,
                'attention'=>'Creating table...'
            ]));
        });

        return true;
    }

    protected function history()
    {}

    protected function turnObserver()
    {
        /**
         * todo: balancer, now first n players
         */
        $playersIds = \DB::table('players')->leftJoin('ratings','players.id','=','ratings.id')
            ->where('');
    }

    protected function getFreePort(): int
    {
        return 8081;
    }
}
