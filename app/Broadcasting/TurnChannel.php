<?php

namespace App\Broadcasting;

use App\Models\Game\Player;
use App\Models\Game\Table;
use App\Models\User;

class TurnChannel
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
     * @return bool
     */
    public function join(User $user,int $userId): bool
    {
        return $user->id === $userId;
    }
}
