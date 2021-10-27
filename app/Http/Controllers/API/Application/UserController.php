<?php

namespace App\Http\Controllers\API\Application;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\User\IndexService;
use App\Services\User\StoreService;
use App\Services\User\UpdateService;

class UserController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('user_index');

        $users = IndexService::run(
            $request->query(), 
            $request->get('relations', []),
            $request->get('itemsPerPage', false),
        );

        return $this->sendResponse($users, 'Users retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('user_add');

        $data = $request->validated();

        $client = StoreService::run($data);

        return $this->sendResponse($client, 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->load(['roles']);

        return $this->sendResponse($user, 'User retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        $this->authorize('user_update');

        if($user->id === $request->user()->id){
            return $this->sendError('Action not allowed', [], 403);
        }
        
        $data = $request->validated();

        $user = UpdateService::run($user, $data);

        return $this->sendResponse($user, 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->sendResponse([], 'User deleted successfully.');
    }
}
