<?php

namespace App\Http\Resources\Game\Api;

use App\Abstracts\AbstractPokerTable;
use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
{
    public static $wrap = false;

    public function toArray($request)
    {
        /**
         * @var AbstractPokerTable $this
         */
        return [
            'screen'=>'table',
            'channel'=>$this->getChannelName().'-table'
        ];
    }
}
