<?php

namespace App\Broadcasting;

use App\Models\Game\Player;
use App\Models\Game\Table;
use App\Models\User;

class UserChannel
{
    public function join(User $user,int $userId)
    {
        return $userId === $user->id;
    }
}
