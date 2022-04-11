<?php

namespace App\Http\Resources\Game\Api;

use App\Abstracts\AbstractPokerTable;
use App\Game\Player;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
{
    public static $wrap = false;
    protected array $table = [];

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->setTable();
        $this->setPlayers();
    }

    public function toArray($request)
    {
        /**
         * @var AbstractPokerTable $this
         */
        return [
            'screen'=>'table',
            'channel'=>$this->getChannelName('table.'.\Auth::id()),
            'table'=> $this->table
        ];
    }

    protected function setTable()
    {
        /**
         * @var AbstractPokerTable $this
         */
        $this->table = [
            'title' => $this->getTitle(),
            'blind' => $this->getBlind(),
            'cardsInHand' => $this->getCardsInHand(),
            'players' => []
        ];
    }

    protected function setPlayers()
    {
        /**
         * @var AbstractPokerTable $this
         */
        $this->eachPlayer(function (Player $player) {
            $user = User::find($player->getUserId());

            $this->table['players'][] = [
                'name' => $user->data?->public_name ?? $user->name,
                'avatar' => ($data = $user->data) ? asset($data->avatar_path) : null,
                'amount' => [
                    'hand' => 1000,
                    'bank' => 0
                ],
            ];
        });
    }
}
