<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Services\Address\IndexAddressService;
use App\Services\Address\SearchCepService;
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
     * @param Request $request
     * @param SearchCepService $searchCepService
     *
     * @return \Illuminate\Http\Response
     */
    public function searchCep(Request $request, SearchCepService $searchCepService)
    {
        $data = $request->all();

        $cep = $searchCepService->run($data['cep'] ?? null);

        if(!$cep){
            return $this->sendError('CEP not found', []);
        }

        return $this->sendResponse($cep, 'CEP');
    }
}
