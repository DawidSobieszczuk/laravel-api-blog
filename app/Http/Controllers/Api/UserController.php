<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function showCurrentUser()
    {
        return new UserResource($this->userService->getCurrentLoggedUser());
    }

    public function showCurrentUserPermissions()
    {
        return $this->response(['data' => $this->userService->getCurrentLoggedUserPermissions()]);
    }

    public function showCurrentUserHasPermission($permission)
    {
        return $this->response(['data' => [
            'permission_name' => $permission,
            'has_permission' => $this->userService->getCurrentLoggedUser()->hasPermissionTo($permission),
        ]]);
    }

    public function updateCurrentUser(Request $request)
    {
        return new UserResource($this->userService->updateCurrentLoggedUserFromRequest($request));
    }
}
