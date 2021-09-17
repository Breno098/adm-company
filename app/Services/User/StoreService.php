<?php

namespace App\Services\User;

use App\Models\User;

class StoreService
{
    /**
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run(array $data = [])
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
