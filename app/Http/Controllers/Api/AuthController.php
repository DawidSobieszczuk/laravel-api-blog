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
        $token = $this->userService->createTokenFromRequest($request);

        if (!$token) return $this->responseUnauthenticated("Email or password not match");

        return $this->response($token, 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->responseMessage('Logout.');
    }
}
