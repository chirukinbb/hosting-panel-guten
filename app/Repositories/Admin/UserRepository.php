<?php

namespace App\Repositories\Admin;

use App\Abstracts\AbstractRepository;
use App\Jobs\SendRegistrationMail;
use App\Models\User;
use \Illuminate\Database\Eloquent\Builder;

class UserRepository extends AbstractRepository
{
    /**
     * @var User
     */
    protected Builder $builder;

    public function setQueryBuilder()
    {
        $this->builder = User::withTrashed();
    }

    public function create(array $attributes)
    {
        /**
         * @var User $user
         */
        $user = parent::create($attributes);
        $user->assignRole('User');
        $user->delete();

        \Queue::push(new SendRegistrationMail($user, $attributes['password']));
    }

    public function show(int $id)
    {
        return $this->builder->with(['data','roles'])
            ->find($id);
    }

    public function removeAllRoles($id)
    {
        $this->builder->find($id)
            ->roles()
            ->detach();
    }

    public function setRole($userId,$roleId)
    {
        $this->builder->find($userId)
            ->assignRole($roleId);
    }
}
