<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = false;

    public function toArray($request)
    {
        /**
         * @var User $this
         */
        return [
            'name'=>$this->name,
            'token'=>$this->createApiToken()
        ];
    }
}
