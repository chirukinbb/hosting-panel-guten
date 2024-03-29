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
        return $this->create($attributes);
    }

    public function trashed(array $attributes)
    {
        /**
         * @var Article $article
         */
        $article = $this->create($attributes);
        $this->hide($article->id);
    }
}
