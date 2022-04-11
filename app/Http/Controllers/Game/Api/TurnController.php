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
use App\Jobs\Game\StartAuctionForPlayerJob;
use App\Jobs\Game\StartPokerRoundJob;
use App\Models\Game\Player;
use App\Models\Game\Table;
use Illuminate\Http\Request;

class TurnController extends Controller
{
    private object $result;
    private bool $toTable = false;

    public function state()
    {
        $player = Player::whereUserId(\Auth::id())
            ->whereNotNull('searched')
            ->first();

        if (!is_null($player)) {
            $table = Table::find($player->searched);

            $table->object->eachPlayer(function (\App\Game\Player $player) use ($table) {
                if ($player->getUserId() === \Auth::id()) {
                    $place = $player->getPlace();

                    $this->result = TurnResource::make(
                        new Turn(
                            $table->object->getCurrentPlayersCount(),
                            $table->object->getChannelName('turn.' . $place)
                        )
                    );
                }

                return 1;
            });

            return $this->result;
        }

        $player = Player::whereUserId(\Auth::id())
            ->whereNotNull('gamed')
            ->first();

        if (!is_null($player)) {
            $table = Table::find($player->gamed);

            return TableResource::make($table->object);
        }

        return json_encode(['screen' => 'list']);
    }

    public function stand(Request $request)
    {
        $className = 'App\Game\Tables\\' . $request->input('tableClass');

        if (!class_exists($className))
            return ErrorResource::make('unable to create table');

        $player = Player::whereUserId(\Auth::id())->first();

        if (!is_null($player)) {
            if (!is_null($player->gamed) || !is_null($player->searched))
                return ErrorResource::make('you already in game');
        }

        $table = Table::whereTableClass($className)->where('status', Table::SEARCHED)
            ->first();

        if (is_null($table)) {
            /**
             * @var AbstractPokerTable $tableObj
             */
            $tableObj = new $className();
            $tableObj->setId(now()->timestamp);
            $place = $tableObj->setPlayer(
                \Auth::id(),
                \Auth::user()->data?->public_name ?? \Auth::user()->name,
                \Auth::user()->data?->avatar_path ?? asset('img/JohnDoe.webp')
            );

            $table = Table::getModel();
            $table->table_class = $className;
            $table->object = $tableObj;
        } else {
            $tableObj = $table->object;
            $place = $tableObj->setPlayer(
                \Auth::id(),
                \Auth::user()->data?->public_name ?? \Auth::user()->name,
                \Auth::user()->data?->avatar_path ?? asset('img/JohnDoe.webp')
            );
        }

        $table->object = $tableObj;
        $table->save();

        $player = Player::whereTableClass($className)->where('user_id', \Auth::id())
            ->first();

        if (is_null($player)) {
            $player = Player::getModel();
            $player->user_id = \Auth::id();
            $player->searched = $table->id;
            $player->table_class = $className;
        } else {
            $player->searched = $table->id;
        }

        $player->save();

        $table->object->eachPlayer(function (\App\Game\Player $player) use ($table) {
            $place = $player->getPlace();

            broadcast(new PlayersUpdateInPokerTableBroadcaster(// посадка нового игрока за стол(БД) + оповещение остальных о нем в очередь
                $table->object->getCurrentPlayersCount(),
                'loader',
                $table->object->getChannelName('turn.' . $place)
            ));

            if ($table->object->getPlayersCount() === $table->object->getCurrentPlayersCount()) {
                if (!$this->toTable) {
                    $this->dispatch(new StartPokerRoundJob($table->id));// Разклад карт на раунд в БД
                    $this->toTable = true;
                }

                $pokerman = Player::whereSearched($table->id)->where('user_id', $player->getUserId())
                    ->first();
                $pokerman->searched = 0;
                $pokerman->gamed = $table->id;
                $pokerman->save();

                broadcast(new CreatePokerTableBroadcaster(// Разсадка игроков в клиенте игры
                    $table->id,
                    'table',
                    $table->object->getChannelName('turn.' . $place),
                    $pokerman->user_id
                ));
            }
        });

        if ($this->toTable) {
            $table->status = Table::CONTINUE;
            $table->save();

            $this->dispatch(new StartAuctionForPlayerJob(// старт таймера на ход игрока в БД
                $table->id,
                'table',
                $table->object->getChannelName('turn.' . $place)
            ));
        }

        return $this->toTable ?
            TableResource::make($table->object) :
            TurnResource::make(
                new Turn(
                    $table->object->getCurrentPlayersCount(),
                    $table->object->getChannelName('turn.' . $place)
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

            $table->object->eachPlayer(function (\App\Game\Player $player) use ($table) {
                $place = $player->getPlace();

                broadcast(new PlayersUpdateInPokerTableBroadcaster(
                    $table->object->getCurrentPlayersCount(),
                    'loader',
                    $table->object->getChannelName('turn.' . $place)
                ));
            });

            $player->searched = null;
            $player->save();
        }

        return json_encode(['screen' => 'list']);
    }
}
