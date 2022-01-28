<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Http\Requests\Tenant\Address\SeachCepRequest;
use App\Services\Address\IndexAddressService;
use App\Services\Address\SearcheCepService;
use Illuminate\Http\Request;

class AddressController extends BaseApiController
{
     /**
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, IndexAddressService $indexAddressService)
    {
        $addresses = $indexAddressService->run(
            $request->query(),
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage', false),
        );

        return $this->sendResponse($addresses, 'Addresses retrieved successfully.');
    }

    /**
     * @param SeachCepRequest $request
     * @param SearcheCepService $searcheCepService
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
