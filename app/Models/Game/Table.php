<?php

namespace App\Models\Game;

use App\Abstracts\AbstractModel;

class Table extends AbstractModel
{
    const SEARCHED = 0;
    const CONTINUE = 1;
    const FINISHED = 2;

    protected $fillable = [
        'round_no',
        'object',
        'status',
        'broadcaster_class',
        'removed_job_id'
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
