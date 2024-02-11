<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * @var User
     */
    public $resource;

    public function toArray($request)
    {
        return [
            'name' => $this->resource->data->public_name ?? $this->resource->name,
            'avatar_url' => $this->resource->data->avatar_path
        ];
    }
}
