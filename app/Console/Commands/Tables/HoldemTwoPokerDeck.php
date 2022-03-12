<?php

namespace App\Console\Commands\Tables;

use Illuminate\Console\Command;

class HoldemTwoPokerDeck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'HoldemTwoPokerDeck:start {--port}';

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
        $this->info('table create for '.serialize($this->argument('playerIds')));
    }
}
