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

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->builder = new PokerTableBuilder($resource, auth()->id());
    }

    public function toArray($request)
    {
        return array_merge(
            ['channel'=>$this->builder->getChannelName('table.',\Auth::id())],
            $this->buildTable()
        );
    }

    protected function buildTable()
    {
        $broadcaster  = Table::find($this->resource)->broadcaster_class;

        // todo: remake this sheets
        return call_user_func([new $broadcaster(
            $this->resource,
            'table',
            $this->builder->getChannelName('table',auth()->id()),
            auth()->id()
        ),'broadcastWith']);
    }
}
