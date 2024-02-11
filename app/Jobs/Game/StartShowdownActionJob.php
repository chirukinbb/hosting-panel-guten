<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Repositories\PokerTableRepository;

class StartShowdownActionJob extends AbstractGameJob
{
    public function action(): PokerTableRepository
    {
    }
}
