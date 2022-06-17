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

    public function show()
    {
        return new UserResource($this->userService->getCurrentLoggedUser());
    }

    public function update(Request $request)
    {
        return new UserResource($this->userService->updateCurrentLoggedUserFromRequest($request));
    }
}
