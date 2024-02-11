<?php

namespace App\Models;

use App\Abstracts\AbstractModel;

class UserData extends AbstractModel
{
    protected $table = 'user_data';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'avatar_path',
        'biography',
        'public_name'
    ];

    public function setAvatarPathAttribute($value)
    {
        $this->attributes['avatar_path'] = empty($value) ? 'https://interesnyefakty.org/wp-content/uploads/chto-takoe-inkognito.jpg' : $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
