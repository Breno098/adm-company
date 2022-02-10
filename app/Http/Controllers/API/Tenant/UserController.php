<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\User\IndexUserService;
use App\Services\User\StoreUserService;
use App\Services\User\UpdateService;
use App\Services\User\UpdateUserService;

class UserController extends BaseApiController
{
    /**
     * @param Request $request
     * @param IndexUserService $indexUserService
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, IndexUserService $indexUserService)
    {
        $this->authorize('user_index');

        $users = $indexUserService->run(
            $request->query(),
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage', false),
        );

        return $this->sendResponse($users, 'Users retrieved successfully.');
    }

    /**
     * @param StoreUserRequest $storeUserRequest
     * @param StoreUserService $storeUserService
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $storeUserRequest, StoreUserService $storeUserService)
    {
        $this->authorize('user_add');

        $data = $storeUserRequest->validated();

        $client = $storeUserService->run($data);

        return $this->sendResponse($client, 'User created successfully.');
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->load(['roles']);

        return $this->sendResponse($user, 'User retrieved successfully.');
    }

    /**
     * @param UpdateUserRequest $updateUserRequest
     * @param UpdateUserService $updateUserService
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateUserRequest $updateUserRequest,
        UpdateUserService $updateUserService,
        User $user
    )
    {
        $this->authorize('user_update');

        // if($user->id === $updateUserRequest->user()->id){
        //     return $this->sendError('Action not allowed', [], 403);
        // }

        $data = $updateUserRequest->validated();

        $user = $updateUserService->run($user, $data);

        return $this->sendResponse($user, 'User updated successfully.');
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->roles()->sync([]);

        $user->delete();

        return $this->sendResponse([], 'User deleted successfully.');
    }
}
