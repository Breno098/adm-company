<?php

namespace App\Services\Company\Images;

use App\Models\Company;
use Illuminate\Support\Facades\Storage;

class MakeLogoService extends MakeImagesService
{
    public function run(array $data = [], Company $company)
    {
        $image = $data['image_logo'] ?? null;

        $this->existsLogo($company);

        return $this->makeImage($image, $company, 'logo');
    }

    private function existsLogo(Company $company)
    {
        if($company->logo){
            $pathReplace = str_replace('storage', '', $company->logo->path);
            if(Storage::exists($pathReplace)){
                Storage::delete($pathReplace);
                $company->logo->delete();
            }
        }
    }
}
