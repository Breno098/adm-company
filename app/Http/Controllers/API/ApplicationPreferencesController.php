<?php

namespace App\Http\Controllers\API;

use App\Services\ApplicationPreferences\IndexActiveService;
use Illuminate\Http\Request;

class ApplicationPreferencesController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $preferences = IndexActiveService::run(
            $request->query(), 
            $request->get('relations', [])
        );

        return $this->sendResponse($preferences, 'Preferences retrieved successfully.');
    }
}
