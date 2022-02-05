<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content'
    ];

    private static array $statuses = [
        'admin/article.trash',
        'admin/article.publish'
    ];

    public function setSlugAttribute($value)
    {
        return $this->attributes['slug'] = $value ?? Str::slug($this->title);
    }

    public function getStatus()
    {
        return $this->deleted_at ? __(self::$statuses[0]) : __(self::$statuses[1]);
    }

    public static function getStatuses()
    {
        return self::$statuses;
    }
}
