<?php

namespace App\Models\Game;

use App\Abstracts\AbstractModel;

class Rating extends AbstractModel
{
    protected $fillable = [
        'player_id',
        'table_class',
        'score'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
