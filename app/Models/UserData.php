<?php

namespace App\Models;

use App\Abstracts\AbstractModel;

class UserData extends AbstractModel
{
    protected $table = 'user_data';

    public $timestamps = false;

    protected $fillable = [
        'avatar_path',
        'biography',
        'public_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
