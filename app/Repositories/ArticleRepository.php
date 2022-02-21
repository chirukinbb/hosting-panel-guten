<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepository
{
    public function getBySlug(string $slug)
    {
        return Article::whereSlug($slug)->first();
    }
}
