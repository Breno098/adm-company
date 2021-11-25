<?php

namespace App\Services\Image;

class LogoBase64
{
    public function run()
    {
        $pathLogo = $this->pathLogo();

        $getContents = file_get_contents($pathLogo);

        return 'data:image/png;base64, '. base64_encode($getContents);
    }

    private function pathLogo()
    {
        return public_path('storage/logo.png');
    }
}
