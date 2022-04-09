<?php

namespace App\Repositories\Admin;

use App\Abstracts\AbstractRepository;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;

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

    public function create(array $attributes)
    {
        if (isset($attributes['thumbnail']) && $attributes['thumbnail'] instanceof UploadedFile)
            /**
             * @var UploadedFile $attributes ['thumbnail']
             */
            $attributes['thumbnail_path'] = $attributes['thumbnail']->storePublicly('articles');

        return parent::create($attributes);
    }

    public function update(array $attributes)
    {
        if (isset($attributes['thumbnail']) && $attributes['thumbnail'] instanceof UploadedFile)
            /**
             * @var UploadedFile $attributes ['thumbnail']
             */
            $attributes['thumbnail_path'] = $attributes['thumbnail']->storePublicly('articles');

        parent::update($attributes);
    }
}
