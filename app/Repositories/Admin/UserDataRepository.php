<?php

namespace App\Repositories\Admin;

use App\Abstracts\AbstractRepository;
use App\Models\UserData;

class UserDataRepository extends AbstractRepository
{

    public function setQueryBuilder()
    {
        $this->builder = UserData::withTrashed();
    }
}
