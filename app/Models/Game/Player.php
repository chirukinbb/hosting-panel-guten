<?php

namespace App\Models\Game;

use App\Abstracts\AbstractModel;
use App\Models\User;

class Player extends AbstractModel
{
    protected $fillable  = [
        'user_id',
        'table_class'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
