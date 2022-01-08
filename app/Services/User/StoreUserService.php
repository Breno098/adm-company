<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Arr;

class StoreUserService
{
    /**
     * @param  array  $data
     *
     * @return mixed
     */
    public function run(array $data = [])
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->roles()->sync(Arr::get($data, 'roles'));

        $user->save();

        return $user;
    }
}
