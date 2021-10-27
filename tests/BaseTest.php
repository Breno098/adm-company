<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;

abstract class BaseTest extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->fetchUser();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->destroyUser();
    }

    protected function login()
    {
        $response = $this->post('api/login', [
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        $this->user->token = $response['token'];
    }

    protected function fetchUser()
    {
        $this->user = User::factory()->create();

        $this->login();

        $this->addAllRolesForUser();
    }

    protected function destroyUser()
    {
        $this->user->roles()->sync([]);
        $this->user->delete();
    }

    protected function addAllRolesForUser()
    {
        Role::all()->map(function($role){
            $this->user->roles()->attach($role);
        }); 
    }
}
