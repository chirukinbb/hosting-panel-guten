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

Broadcast::channel('turn-{table_id}', \App\Broadcasting\TurnChannel::class);
Broadcast::channel('table-{table_id}', \App\Broadcasting\TableChannel::class);
