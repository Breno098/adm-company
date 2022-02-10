<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Services\Role\IndexGroupByTagRoleService;
use App\Services\Role\IndexRoleService;
use App\Services\Role\IndexService;
use Illuminate\Http\Request;

class RoleController extends BaseApiController
{
    /**
     * @param Request $request
     * @param IndexRoleService $indexRoleService
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, IndexRoleService $indexRoleService)
    {
        $roles = $indexRoleService->run(
            $request->query(),
            $request->get('relations', []),
            $request->get('itemsPerPage', false),
        );

        return $this->sendResponse($roles, 'Roles retrieved successfully.');
    }

    /**
     * @param Request $request
     * @param IndexGroupByTagRoleService $indexGroupByTagRoleService
     *
     * @return \Illuminate\Http\Response
     */
    public function indexGroupByTag(Request $request, IndexGroupByTagRoleService $indexGroupByTagRoleService)
    {
        $roles = $indexGroupByTagRoleService->run(
            $request->query(),
            $request->get('relations', []),
            $request->get('itemsPerPage', false),
        );

        return $this->sendResponse($roles, 'Roles group by tag retrieved successfully.');
    }
}
