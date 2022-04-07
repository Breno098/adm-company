<?php

namespace App\Services\Service;

use App\Models\Service;
use Exception;

class DeleteServiceService
{
    /**
     * @param Service $service
     *
     * @return null|bool
     */
    public function run(Service $service): ?bool
    {
        if($service->orders->count()) {
            throw new Exception("Este serviço tem vinculos com um ou mais pedidos. Para deletar o serviço, delete os pedidos vinculados.");
        }

        return $service->delete();
    }
}
