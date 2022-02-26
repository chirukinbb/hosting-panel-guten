<?php

namespace App\Repositories\Admin;

use App\Abstracts\AbstractRepository;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;

class ArticleRepository extends AbstractRepository
{
    /**
     * @var Article
     */
    protected Builder $builder;

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
