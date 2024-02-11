<?php

namespace App\Http\Resources\Game\Api;

use App\Abstracts\AbstractPokerTable;
use App\Builders\PokerTableBuilder;
use App\Game\Player;
use App\Models\Game\Table;
use App\Models\User;
use App\Repositories\PokerTableRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
{
    public static $wrap = false;
    protected array $table = [];
    protected PokerTableBuilder $builder;

    public function toArray($request)
    {
        return array_merge(
            [
                'channel'=>$this->builder->getChannelName('table',\Auth::id()),
                'table'=> $this->builder->getTable(),
                'screen'=>'table'
            ]
        );
    }
}
