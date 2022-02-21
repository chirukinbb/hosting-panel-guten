<?php

namespace App\Repositories\Admin;

use App\Abstracts\AbstractRepository;
use App\Models\Article;

class ArticleRepository extends AbstractRepository
{
    public function setQueryBuilder()
    {
        $this->builder = Article::withTrashed();
    }

    public function published(array $attributes)
    {
        $this->create($attributes);
    }

    public function trashed(array $attributes)
    {
        $articleId = $this->create($attributes);
        $this->hide($articleId);
    }
}
