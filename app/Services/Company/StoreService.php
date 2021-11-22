<?php

namespace App\Services\Company;

use App\Models\Company;

class StoreService
{
    /**
     * @param  array  $data
     *
     * @return mixed
     */
    public function run(array $data = [])
    {
        $company = Company::create($data);

        foreach ($data['contacts'] ?? [] as $contact) {
            $verifyContact = isset($contact['contact']);
            if($verifyContact){
                $company->contacts()->create($contact);
            }
        }

        foreach ($data['addresses'] ?? [] as $address) {
            $verifyAddress = isset($address['street']) || isset($address['city']) || isset($address['cep']) || isset($address['apartment']);
            if($verifyAddress){
                $company->addresses()->create($address);
            }
        }

        foreach ($data['bank_accounts'] ?? [] as $bankAccount) {
            $company->bankAccounts()->create($bankAccount);
        }

        $company->save();

        $company->makeDirectory();

        return $company;
    }
}
