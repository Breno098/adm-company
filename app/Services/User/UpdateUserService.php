<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Arr;

class UpdateUserService
{
    /**
     * @param  mixed  $id
     * @param  array  $data
     *
     * @return mixed
     */
    public function run(User $user, array $data = [])
    {
        $user->update($data);

        $user->roles()->sync(Arr::get($data, 'roles'));

        $user->save();

        return $user;
    }
}
