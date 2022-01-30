<?php

namespace App\Models\Account;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'user_settings';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'avatar_url',
        'biography'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
