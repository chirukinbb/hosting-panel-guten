<?php

namespace App\Console\Commands;

use App\Repositories\Admin\UserRepository;
use App\Sockets\AppSocket;
use Illuminate\Console\Command;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class AppSocketCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:start';

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
            new AppSocket(
                new UserRepository(),
                new \SplObjectStorage()
            )
        );

        $server = IoServer::factory(
            new HttpServer($ws),
            8080
        );
        $server->run();
    }
}
