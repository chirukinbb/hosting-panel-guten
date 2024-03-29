<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'value'
    ];

    public static function all($columns = ['*'])
    {
        $settings = [];

        foreach (parent::all($columns) as $setting){
            $settings[$setting->name] = $setting->value;
        }

        return $settings;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
