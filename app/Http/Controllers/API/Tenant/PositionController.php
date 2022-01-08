<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Services\Position\IndexPositionService;
use Illuminate\Http\Request;

class PositionController extends BaseApiController
{
    /**
     * @param Request $request
     * @param IndexPositionService $indexPositionService
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, IndexPositionService $indexPositionService)
    {
        $position = $indexPositionService->run(
            $request->query(),
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
        );

        return $this->sendResponse($position, 'Positions retrieved successfully.');
    }
}
