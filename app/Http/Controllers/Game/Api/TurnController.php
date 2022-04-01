<?php

namespace App\Http\Controllers\Game\Api;

use App\Abstracts\AbstractPokerTable;
use App\Events\Game\Broadcasters\CreatePokerTableEvent;
use App\Events\Game\Broadcasters\PlayersUpdateInPokerTableEvent;
use App\Game\Turn;
use App\Http\Controllers\Controller;
use App\Http\Resources\Game\Api\ErrorResource;
use App\Http\Resources\Game\Api\TableResource;
use App\Http\Resources\Game\Api\TurnResource;
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
            $table = Table::find($player->searched);

            return TurnResource::make(
                new Turn(
                    $table->object->getCurrentPlayersCount(),
                    $table->object->getChannelName('turn')
                )
            );
        }

        $player = Player::whereUserId(\Auth::id())
            ->whereNotNull('gamed')
            ->first();

        if (!is_null($player)) {
            $table = Table::find($player->gamed);

            return TableResource::make($table->object);
        }

        return json_encode(['screen'=>'list']);
    }

    public function stand(Request $request)
    {
        $className = 'App\Game\Tables\\'.$request->input('tableClass');

        if (!class_exists($className))
            return ErrorResource::make('unable to create table');

        $player = Player::whereUserId(\Auth::id())->first();

        if (!is_null($player)) {
            if (!is_null($player->gamed) || !is_null($player->searched))
                return ErrorResource::make('you already in game');
        }

        $table = Table::whereTableClass($className)->where('status',Table::SEARCHED)
            ->first();

        if (is_null($table)) {
            /**
             * @var AbstractPokerTable $tableObj
             */
            $tableObj = new $className();
            $tableObj->setId(now()->timestamp);
            $tableObj->setPlayer(
                \Auth::id(),
                \Auth::user()->data?->public_name ?? \Auth::user()->name,
                \Auth::user()->data?->avatar_path
            );

            $table = Table::getModel();
            $table->table_class = $className;
            $table->object = $tableObj;
        } else {
            $tableObj  = $table->object;
            $tableObj->setPlayer(
                \Auth::id(),
                \Auth::user()->data?->public_name ?? \Auth::user()->name,
                \Auth::user()->data?->avatar_path
            );
        }

        $table->object = $tableObj;
        $table->save();

        broadcast(new PlayersUpdateInPokerTableEvent(
            $table->object->getCurrentPlayersCount(),
            'loader',
            $table->object->getChannelName('turn')
        ));

        $player = Player::whereTableClass($className)->where('user_id',\Auth::id())
            ->first();

        if (is_null($player)) {
            $player  = Player::getModel();
            $player->user_id = \Auth::id();
            $player->searched = $table->id;
            $player->table_class = $className;
        } else {
            $player->searched = $table->id;
        }

        $player->save();

        if ($table->object->getPlayersCount() === $table->object->getCurrentPlayersCount()) {
            $table->object->startRound(1);
            $table->object->changeStatuses(0);
            $table->object->payBlinds();
            $table->object->preFlop();
            $table->object->eachPlayer(function (\App\Game\Player $player) use ($table) {
                $pokerman   = Player::whereSearched($table->id)->where('user_id',$player->getPlayerId())
                    ->first();
                $pokerman->searched = null;
                $pokerman->gamed = $table->id;
                $pokerman->save();

                broadcast(new CreatePokerTableEvent(
                    $table->id,
                    'table',
                    $table->object->getChannelName('turn')
                ));
            });
        }

        return TurnResource::make(
            new Turn(
                $table->object->getCurrentPlayersCount(),
                $table->object->getChannelName('turn')
            )
        );
    }

    public function leave()
    {
        $player = Player::whereUserId(\Auth::id())
            ->whereNotNull('searched')
            ->first();

        if (!is_null($player)) {
            $table = Table::find($player->searched);
            $tableObj = $table->object;
            $tableObj->removePlayer(\Auth::id());
            $table->object = $tableObj;
            $table->save();

            broadcast(new PlayersUpdateInPokerTableEvent(
                $table->object->getCurrentPlayersCount(),
                'loader',
                $table->object->getChannelName('turn')
            ));

            $player->searched = null;
            $player->save();
        }

        return json_encode(['screen'=>'list']);
    }
}
