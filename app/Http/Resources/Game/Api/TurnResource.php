<?php

namespace App\Http\Resources\Game\Api;

use App\Game\Turn;
use Illuminate\Http\Resources\Json\JsonResource;

class TurnResource extends JsonResource
{
    public static $wrap = false;
    /**
     * @var Turn
     */
    public $resource;

    public function toArray($request)
    {
        return [
            'count'=>$this->count,
            'channel'=>$this->channel,
            'screen'=>'loader',
            'listen'=>'turn'
        ];
    }
}
// php artisan websockets:serve
// php artisan queue:work
