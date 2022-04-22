<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Contact;
use App\Models\Order;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;


class AppSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Contact::all()->map(function(Contact $contact){
            if($contact->company_id) {
                $contact->update([
                    'owner_id' => $contact->company_id,
                    'owner_type' => 'Company'
                ]);
            }

            if($contact->employee_id) {
                $contact->update([
                    'owner_id' => $contact->employee_id,
                    'owner_type' => 'Employee'
                ]);
            }

            if($contact->client_id) {
                $contact->update([
                    'owner_id' => $contact->client_id,
                    'owner_type' => 'Client'
                ]);
            }
        });


    }
}
