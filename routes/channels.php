<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('turn-{table_id}', function (\App\Models\User $user, $tableId) {
    $table = \App\Models\Game\Player::whereUserId($user->id)->where('searched', $tableId)
        ->first();

    return !is_null($table);
});

Broadcast::channel('table-{table_id}', function (\App\Models\User $user, $tableId) {
    $table = \App\Models\Game\Player::whereUserId($user->id)->where('gamed', $tableId)
        ->first();

    return !is_null($table);
});
