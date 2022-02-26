<?php

namespace App\Repositories\Admin;

use App\Abstracts\AbstractRepository;
use App\Jobs\SendRegistrationMail;
use App\Models\User;
use App\Models\UserData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;

class UserDataRepository extends AbstractRepository
{
    /**
     * @var UserData
     */
    protected Builder $builder;

    public function setQueryBuilder()
    {
        $this->builder = UserData::withTrashed();
    }

    public function getList(int $perPage = 10)
    {}

    public function create(array $attributes)
    {

        $user = User::create($attributes);
        $user->assignRole('User');
        $user->delete();

        \Queue::push(new SendRegistrationMail($user, $attributes['password']));

        return redirect()->route('admin.user.edit', ['user' => $user->id]);
    }

    public function update(array $attributes)
    {
        /**
         * @var UploadedFile $attributes ['avatar']
         */
        $attributes = array_merge(
            $attributes,
            (isset($attributes['avatar']) && $attributes['avatar'] instanceof UploadedFile) ?
                ['avatar_path'=>$attributes['avatar']->storePublicly('avatars')] :
                []
        );

        $data = $this->builder->where('user_id', $attributes['user_id'])->first();

        if (is_null($data)) {
            $this->builder->create($attributes);
        }else {
            /**
             * @var Builder $data
             */
            $data->update((array)$attributes);
        }
    }
}
