<?php

namespace App\Services\Company;

use App\Models\Company;
use Illuminate\Support\Arr;

class UpdateService
{
    /**
     * @param  mixed  $id
     * @param  array  $data
     *
     * @return mixed
     */
    public function run(Company $company, array $data = [])
    {
        $company->update($data);

        $company->contacts()->delete();
        $company->addresses()->delete();

        foreach ($data['contacts'] ?? [] as $contact) {
            $id = isset($contact['id']) ? $contact['id'] : false;

            if($id){
                $contactcompany = $company->contacts()->withTrashed()->find($id);
                $contactcompany->restore();
                $contactcompany->update($contact);
            } else {
                $verifyContact = isset($contact['contact']);
                if($verifyContact){
                    $company->contacts()->create($contact);
                }
            }
        }

        foreach ($data['addresses'] ?? [] as $address) {
            $id = isset($address['id']) ? $address['id'] : false;

            if($id){
                $addresscompany = $company->addresses()->withTrashed()->find($id);
                $addresscompany->restore();
                $addresscompany->update($address);
            } else {
                $verifyAddress = isset($address['street']) || isset($address['city']) || isset($address['cep']) || isset($address['apartment']);
                if($verifyAddress){
                    $company->addresses()->create($address);
                }
            }
        }

        foreach ($data['bank_accounts'] ?? [] as $bankAccount) {
            $id = isset($bankAccount['id']) ? $bankAccount['id'] : false;

            if($id){
                $bankAccountcompany = $company->bankAccounts()->withTrashed()->find($id);
                $bankAccountcompany->restore();
                $bankAccountcompany->update($bankAccount);
            } else {
                $company->bankAccounts()->create($address);
            }
        }

        $company->save();

        return $company;
    }
}
