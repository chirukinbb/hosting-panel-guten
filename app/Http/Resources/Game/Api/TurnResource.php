<?php

namespace App\Http\Resources\Game\Api;

use App\Game\Turn;
use Illuminate\Http\Resources\Json\JsonResource;

class TurnResource extends JsonResource
{
    public static $wrap = false;

    public function toArray($request)
    {
        /**
         * @var Turn $this
         */
        return [
            'count'=>$this->count,
            'channel'=>$this->channel,
            'screen'=>'loader',
            'listen'=>'NewUserAfterTableEvent'
        ];
    }
}
// php artisan websockets:serve
// php artisan queue:work
