<?php

namespace App\Models\Game;

use App\Abstracts\AbstractModel;

class Table extends AbstractModel
{
    const CONTINUE = 1;
    const FINISHED = 2;

    protected $fillable = [
        'object',
        'status'
    ];

    public function setObjectAttribute($value)
    {
        $this->attributes['object'] = serialize($value);
    }

    public function getObjectAttribute()
    {
        return unserialize($this->attributes['object']);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
