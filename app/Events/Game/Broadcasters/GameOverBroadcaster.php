<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;
use App\Builders\PokerTableBuilder;
use \App\Models\Game\Player;
use Illuminate\Database\Eloquent\Builder;

class GameOverBroadcaster  extends AbstractBroadcaster
{
    public function action(): PokerTableBuilder
    {
        Player::whereUserId($this->userId)->where('table_class',function (Builder $builder) {
            $builder->where('id',$this->tableId)->get('table_class');
        })->increment('rating',$this->builder->getRatingByTable());

        return $this->builder->losers();
    }
}
