<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AppSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::create([
            'name' => 'Desentupidora Crispim',
            'cnpj' => '17.107.361/0001-34',
            'fantasy_name' =>'Desentupidora Crispim',
        ]);

        Address::create([
            'number' => 350,
            'district' => 'PQ. FLAMBOYANS',
            'city' => ' RIBEIRÃO PRETO',
            'state' => 'SP',
            'street' => 'LEONOR PENNACHIOTTI GALLO',
            'cep' => '14093-651',
            'company_id' => $company->id
        ]);

        User::create([
            'name' => 'Agatha Crispim',
            'email' => 'agathacrispim66@gmail.com',
            'password' => Hash::make('senhapadrao'),
            'company_id' => $company->id
        ]);

        User::all()->map(function($user) use ($company){
            $user->company_id = $company->id;
            $user->save();
            
            Role::all()->map(function($role) use ($user){
                $user->roles()->attach($role);
            }); 
        });

        Client::all()->map(function($model) use ($company){
            $model->company_id = $company->id;
            $model->save();
        });

        Product::all()->map(function($model) use ($company){
            $model->company_id = $company->id;
            $model->save();
        });

        Service::all()->map(function($model) use ($company){
            $model->company_id = $company->id;
            $model->save();
        });

        Order::all()->map(function($model) use ($company){
            $model->company_id = $company->id;
            $model->save();
        });

        Address::all()->map(function($model) use ($company){
            $model->company_id = $company->id;
            $model->save();
        });

        Contact::all()->map(function($model) use ($company){
            $model->company_id = $company->id;
            $model->save();
        });

        Appointment::all()->map(function($model) use ($company){
            $model->company_id = $company->id;
            $model->save();
        });

        Address::all()->map(function($model) use ($company){
            $model->company_id = $company->id;
            $model->save();
        });
    }
}
