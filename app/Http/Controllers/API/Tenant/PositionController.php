<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Services\Position\IndexService;
use Illuminate\Http\Request;

class PositionController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $position = IndexService::run(
            $request->query(),
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
        );

        return $this->sendResponse($position, 'Positions retrieved successfully.');
    }
}
