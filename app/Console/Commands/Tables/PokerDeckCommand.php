<?php

namespace App\Console\Commands\Tables;

use App\Repositories\Admin\UserRepository;
use App\Sockets\PlayersCollection;
use App\Sockets\TableSocket;
use Illuminate\Console\Command;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Loop;

class PokerDeckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PokerDeck:start {--port} {--table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $ws = new WsServer(
            new TableSocket(
                $this->argument('--table'),
                new UserRepository(),
                new PlayersCollection(),
                $this->argument('--port'),
                Loop::get()
            )
        );

        $server = IoServer::factory(
            new HttpServer($ws),
            $this->argument('--port')
        );
        $server->run();
    }
}
