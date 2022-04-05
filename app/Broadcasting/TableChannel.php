<?php

namespace App\Broadcasting;

use App\Models\Game\Player;
use App\Models\Game\Table;
use App\Models\User;

class TableChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Models\User  $user
     * @return array|bool
     */
    public function join(User $user,int $place, int $tableId)
    {
        $player = Player::whereUserId($user->id)
            ->whereNotNull('gamed')
            ->first();

        if (is_null($player))
            return false;

        $table  = Table::find($player->gamed);

        if (is_null($table))
            return false;

        return $table->object->getId() === $tableId;
    }
}
