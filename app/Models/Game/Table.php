<?php

namespace App\Models\Game;

use App\Abstracts\AbstractModel;

class Table extends AbstractModel
{
    const SEARCHED = 0;
    const CONTINUE = 1;
    const FINISHED = 2;

    protected $fillable = [
        'table_class',
        'round_no',
        'object',
        'status'
    ];

    protected $casts = [
        'object'=>'object'
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
