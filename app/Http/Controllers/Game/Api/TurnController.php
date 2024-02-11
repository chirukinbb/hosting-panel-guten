<?php

namespace App\Http\Controllers\Game\Api;

use App\Events\PokerTableSearchingEvent;
use App\Events\PokerTableStateEvent;
use App\Http\Controllers\Controller;
use App\Jobs\Game\CreatePokerTableJob;
use App\Jobs\Game\WaitingPlayersCountJob;
use App\Models\Game\Player;
use App\Models\Game\Table;

class TurnController extends Controller
{
    public function state()
    {
        $player = Player::whereUserId(\Auth::id())
            ->whereNotNull('searched')
            ->first();

        if (!is_null($player)) {
            broadcast(new PokerTableSearchingEvent($player->table_class, \Auth::id(), \App\Models\Game\Player::whereTableClass($player->table_class)->whereNotNull('searched')->count()));
        }

        $player = Player::whereUserId(\Auth::id())
            ->whereNotNull('gamed')
            ->first();

        if (!is_null($player)) {
            $table = Table::find($player->gamed);
            broadcast(new PokerTableStateEvent($table, \Auth::id()));
        }

        return response()->json(0);
    }

    public function stand(string $className)
    {
        $className = 'App\Game\Tables\\' . $className;

        if (class_exists($className)) {
            $player = Player::whereUserId(\Auth::id())->where('table_class', $className)->first();

            if (!is_null($player)) {
                if ($player->gamed) {
                    $table = Table::find($player->gamed);
                    broadcast(new PokerTableStateEvent($table, \Auth::id()));
                }

                if ($player->searched) {
                    $table = Table::find($player->searched);
                    broadcast(new PokerTableSearchingEvent($table, \Auth::id(), \App\Models\Game\Player::whereTableClass($className)->whereNotNull('searched')->count()));
                }
            }

            $player = Player::updateOrCreate(
                [
                    'table_class' => $className,
                    'user_id' => \Auth::id()
                ],
                ['searched' => 1]
            );

            // оповещение о новом игроке в очереди
            dispatch(new WaitingPlayersCountJob($className));
            // создание стола и розсадка игроков за стол(БД)
            if (\App\Models\Game\Player::whereTableClass($className)->whereNotNull('searched')->count() >= $className::PLAYER_COUNT) {
                dispatch(new CreatePokerTableJob($className))->afterResponse();
            }
        }

        return response()->json(0);
    }

    public function leave()
    {
        $player = Player::whereUserId(\Auth::id())
            ->whereNotNull('searched')
            ->first();

        if (!is_null($player)) {
            $player->searched = null;
            $player->save();

            // оповещение об уходе игрока из очереди
            dispatch(new WaitingPlayersCountJob($player->table_class));
        }

        return json_encode(['screen' => 'list']);
    }
}
