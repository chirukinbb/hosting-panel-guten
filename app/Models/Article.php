<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use Illuminate\Support\Str;

class Article extends AbstractModel
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_content',
        'keywords',
        'thumbnail_path',
        'type'
    ];

    public static array $statuses = [
        'admin/article.trash',
        'admin/article.publish'
    ];

    public static array $types = [
        'Post',
        'Page'
    ];

    public function setSlugAttribute($value)
    {
        return $this->attributes['slug'] = $value ?? Str::slug($this->title);
    }

    public function setMetaContentAttribute($value)
    {
        return $this->attributes['meta_content'] = $value ?? strip_tags($this->content);
    }

    public function getStatus()
    {
        return $this->deleted_at ? __(self::$statuses[0]) : __(self::$statuses[1]);
    }
}
