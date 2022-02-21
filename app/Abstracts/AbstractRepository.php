<?php

namespace App\Abstracts;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractRepository
{
    protected Builder $builder;

    abstract public function setQueryBuilder();

    public function __construct()
    {
        $this->setQueryBuilder();
    }

    public function create(array $attributes)
    {
        $result = $this->builder->create($attributes);

        return $result->id;
    }

    public function update(array $attributes)
    {
        $this->builder->find($attributes['id'])
            ->update($attributes);
    }

    public function getList(int $perPage = 10)
    {
        return $this->builder->paginate($perPage);
    }

    public function show(int $id)
    {
        return $this->builder->find($id);
    }

    public function destroy($id)
    {
        $this->builder->find($id)->forceDelete();
    }

    public function hide($id)
    {
        $this->builder->find($id)->delete();
    }

    public function restore($id)
    {
        $this->builder->find($id)->restore();
    }
}
