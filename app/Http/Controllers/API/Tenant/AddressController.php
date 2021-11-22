<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Services\Address\IndexService;
use App\Services\Address\SearcheCep;
use Illuminate\Http\Request;

class AddressController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $addresses = IndexService::run(
            $request->query(),
            $request->get('relations', []),
            $request->get('itemsPerPage', false),
        );

        return $this->sendResponse($addresses, 'Addresses retrieved successfully.');
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchCep(Request $request)
    {
        $cep = SearcheCep::run( $request->get('cep') );

        if(!$cep){
            return $this->sendError('CEP not found', []);
        }

        return $this->sendResponse($cep, 'CEP');
    }
}
