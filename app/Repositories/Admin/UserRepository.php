<?php

namespace App\Repositories\Admin;

use App\Abstracts\AbstractRepository;
use App\Jobs\SendRegistrationMail;
use App\Models\User;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use function PHPUnit\Framework\isNull;

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

        return $user;
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

    public function authToken(array $attributes)
    {
        $user = $this->builder->where('email', $attributes['email'])
            ->first();

        if (Hash::check($attributes['password'], $user->password)) {
            return $user->createApiToken();
        }

        return false;
    }

    public function exists($email): bool
    {
        return $this->builder->where('email', $email)
            ->exists();
    }

    public function whereApiToken(string $token)
    {
        $tokenData = PersonalAccessToken::findToken($token)->toArray();

        return $this->builder->find($tokenData['tokenable_id']);
    }
}
