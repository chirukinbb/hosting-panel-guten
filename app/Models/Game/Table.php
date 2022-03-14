<?php

namespace App\Models\Game;

use App\Abstracts\AbstractModel;

class Table extends AbstractModel
{
    protected $fillable = [
        'table_class',
        'round_no',
        'object'
    ];

    protected $casts = [
        'object'=>'object'
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
