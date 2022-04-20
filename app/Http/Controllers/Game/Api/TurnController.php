<?php

namespace App\Http\Controllers\Game\Api;

use App\Abstracts\AbstractPokerTable;
use App\Events\Game\Broadcasters\CreatePokerTableBroadcaster;
use App\Events\Game\Broadcasters\PlayersUpdateInPokerTableBroadcaster;
use App\Game\Turn;
use App\Http\Controllers\Controller;
use App\Http\Resources\Game\Api\ErrorResource;
use App\Http\Resources\Game\Api\TableResource;
use App\Http\Resources\Game\Api\TurnResource;
use App\Jobs\Game\CreatePokerTableJob;
use App\Jobs\Game\PlayersUpdateInTurnJob;
use App\Jobs\Game\StartAuctionForPlayerJob;
use App\Jobs\Game\StartPokerRoundJob;
use App\Models\Game\Player;
use App\Models\Game\Table;
use Illuminate\Http\Request;

class TurnController extends Controller
{
    public function state()
    {
        $player = Player::whereUserId(\Auth::id())
            ->whereNotNull('searched')
            ->first();

        if (!is_null($player)) {

            return TurnResource::make(
                new Turn(
                    \App\Models\Game\Player::whereTableClass($player->table_class)->whereNotNull('searched')->count(),
                    'turn.'.$player->user_id
                )
            );
        }

        $player = Player::whereUserId(\Auth::id())
            ->whereNotNull('gamed')
            ->first();

        if (!is_null($player)) {

            return TableResource::make($player->gamed);
        }

        return json_encode(['screen' => 'list']);
    }

    public function stand(Request $request)
    {
        $className = 'App\Game\Tables\\' . $request->input('tableClass');

        if (!class_exists($className))
            return ErrorResource::make('unable to create table');

        $player = Player::whereUserId(\Auth::id())->where('table_class',$className)->first();

        if (!is_null($player)) {
            if (!is_null($player->gamed) || !is_null($player->searched))
                return ErrorResource::make('you already in game');
        }

        $player = Player::updateOrCreate(
            [
                'table_class' => $className,
                'user_id' => \Auth::id()
            ],
            ['searched' => 1]
        );

        // оповещение о новом игроке в очереди
        dispatch(new PlayersUpdateInTurnJob($className));
        // создание стола и розсадка игроков за стол(БД)
        if (\App\Models\Game\Player::whereTableClass($className)->whereNotNull('searched')->count() >= $className::$count) {
            dispatch(new CreatePokerTableJob($className,'loader'))->delay(now()->addSecond());
        }

        return TurnResource::make(
            new Turn(
                \App\Models\Game\Player::whereTableClass($player->table_class)->whereNotNull('searched')->count(),
                'turn.'.$player->user_id
            )
        );
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
            dispatch(new PlayersUpdateInTurnJob($player->table_class));
        }

        return json_encode(['screen' => 'list']);
    }
}
