<?php

namespace App\Http\Resources\Game\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    public static $wrap = false;

    public function toArray($request)
    {
        return [
            'screen'=>'error',
            'error'=>'action not available',
            'message'=>$this->resource
        ];
    }
}
