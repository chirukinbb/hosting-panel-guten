<?php

namespace App\Models\Game;

use App\Abstracts\AbstractModel;
use App\Models\User;

class Player extends AbstractModel
{
    protected $fillable  = [
        'user_id',
        'table_class',
        'count_tables',
        'rating',
        'searched',
        'gamed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tables()
    {
        return $this->belongsToMany(Table::class);
    }
}
