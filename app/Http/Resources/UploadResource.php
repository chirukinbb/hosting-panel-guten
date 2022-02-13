<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UploadResource extends JsonResource
{
    public static $wrap = false;

    public function toArray($request)
    {
        return [
            'url'=>asset($this->resource)
        ];
    }
}
