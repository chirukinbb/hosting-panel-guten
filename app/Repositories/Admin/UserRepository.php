<?php

namespace App\Repositories\Admin;

use App\Abstracts\AbstractRepository;
use App\Models\User;

class UserRepository extends AbstractRepository
{

    public function setQueryBuilder()
    {
        $this->builder = User::withTrashed();
    }
}
