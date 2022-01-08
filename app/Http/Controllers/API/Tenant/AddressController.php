<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Http\Requests\Tenant\Address\SeachCepRequest;
use App\Services\Address\SearcheCepService;
use Illuminate\Http\Request;

class AddressController extends BaseApiController
{
    /**
     * @param  SeachCepRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function searchCep(SeachCepRequest $request, SearcheCepService $searcheCepService)
    {
        $data = $request->validated();

        $cep = $searcheCepService->run($data['cep']);

        if(!$cep){
            return $this->sendError('CEP not found', []);
        }

        return $this->sendResponse($cep, 'CEP');
    }
}
