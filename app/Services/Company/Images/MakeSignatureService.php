<?php

namespace App\Services\Company\Images;

use App\Models\Company;
use Illuminate\Support\Facades\Storage;

class MakeSignatureService extends MakeImagesService
{
    public function run(array $data = [], Company $company)
    {
        $image = $data['image_signature'] ?? null;

        $this->existsSignature($company);

        return $this->makeImage($image, $company, 'signature');
    }

    private function existsSignature(Company $company)
    {
        if($company->signature){
            $pathReplace = str_replace('storage', '', $company->signature->path);
            if(Storage::exists($pathReplace)){
                Storage::delete($pathReplace);
                $company->signature->delete();
            }
        }
    }
}
