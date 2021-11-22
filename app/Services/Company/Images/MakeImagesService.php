<?php

namespace App\Services\Company\Images;

use App\Models\Company;
use Illuminate\Http\UploadedFile;

class MakeImagesService
{
    protected function makeImage($image, Company $company, string $tag)
    {
        if($image instanceof UploadedFile){
            if($this->upload($image, $company)){
                return $this->createImageDataBase($image, $company, $tag);
            };
        }

        return false;
    }

    protected function upload($image, Company $company)
    {
        if(!$image){
            return null;
        }

        if(!$image->storeAs($company->path, $image->getClientOriginalName())){
            return null;
        }

        return true;
    }

    public function createImageDataBase($image, Company $company, string $tag)
    {
        $company->images()->create([
            'name' => $image->getClientOriginalName(),
            'path' => "{$company->path}/{$image->getClientOriginalName()}",
            'tag' => $tag
        ]);
    }
    
}
