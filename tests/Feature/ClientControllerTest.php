<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Client;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\BaseTest;

class ClientControllerTest extends BaseTest
{
    /**
     * @test
     */
    public function getClients()
    {
        $this->withHeader('Authorization', "Bearer {$this->user->token}")
            ->get('api/client')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'success'
            ]);
    }

    /**
     * @test
     */
    public function getClientsPaginated()
    {
        $this->withHeader('Authorization', "Bearer {$this->user->token}")
            ->get('api/client', [
                'itemsPerPage' => 10
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'success'
            ]);
    }

    /**
     * @test
     */
    public function createClientWithoutContactsAndWithoutAddresses()
    {
        $client = Client::factory()->make();

        $this->withHeader('Authorization', "Bearer {$this->user->token}")
            ->post('api/client', $client->toArray())
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'success'
            ]);
    }

    /**
     * @test
     */
    public function createClientWithoutContactsAndWithAddresses()
    {
        $client = Client::factory()->make();

        $addresses = Address::factory()->count(2)->make();

        $client->addresses = $addresses->toArray();

        $this->withHeader('Authorization', "Bearer {$this->user->token}")
            ->post('api/client', $client->toArray())
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'success'
            ]);
    }

     /**
     * @test
     */
    public function createClientWithContactsAndWithoutAddresses()
    {
        $client = Client::factory()->make();

        $contacts = Contact::factory()->count(2)->make();

        $client->contacts = $contacts->toArray();

        $this->withHeader('Authorization', "Bearer {$this->user->token}")
            ->post('api/client', $client->toArray())
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'success'
            ]);
    }

    /**
     * @test
     */
    public function createClientWithContactsAndWithAddresses()
    {
        $client = Client::factory()->make();

        $contacts = Contact::factory()->count(2)->make();

        $client->contacts = $contacts->toArray();

        $addresses = Address::factory()->count(2)->make();

        $client->addresses = $addresses->toArray();

        $this->withHeader('Authorization', "Bearer {$this->user->token}")
            ->post('api/client', $client->toArray())
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'success'
            ]);
    }



}
