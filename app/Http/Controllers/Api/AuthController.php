<?php

namespace App\Http\Controllers\Api;

use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends ApiController
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(Request $request)
    {
        $data = $this->userService->createTokenFromRequest($request);

        if (!$data) return $this->responseUnauthenticated("Email or password not match");

        return $this->response(['data'=> $data, 'message' => 'User logged'], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->responseMessage('User logged out');
    }
}
