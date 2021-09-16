<?php

namespace App\Http\Controllers\API;

use App\Services\Role\IndexService;
use Illuminate\Http\Request;

class RoleController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = IndexService::run(
            $request->query(), 
            $request->get('relations', []),
            $request->get('itemsPerPage', false),
        );

        return $this->sendResponse($roles, 'Roles retrieved successfully.');
    }
}
