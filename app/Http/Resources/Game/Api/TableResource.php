<?php

namespace App\Http\Resources\Game\Api;

use App\Abstracts\AbstractPokerTable;
use App\Game\Player;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
{
    public static $wrap = false;
    protected $users = [];

    public function __construct($resource)
    {
        parent::__construct($resource);
        // todo: wtf?
        $this->setPlayers();
    }

    public function toArray($request)
    {
        /**
         * @var AbstractPokerTable $this
         */
        return [
            'screen'=>'table',
            'channel'=>$this->getChannelName('turn'),
            'users'=> $this->users
        ];
    }

    protected function setPlayers(AbstractPokerTable $table)
    {
        $table->eachPlayer(function (Player $player) {
            $user = User::find($player->getPlayerId());

            $this->users[] = (object) [
                'place'=>$player->getPlace(),
                'avatar'=>$user->data->avatar_path,
                'name'=>$user->data->public_name
            ];
        });
    }
}
