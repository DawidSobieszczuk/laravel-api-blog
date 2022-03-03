<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    private string $tokenName = 'TokenName';

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        // Check email and password
        $message = "Email or password not match";
        if (!$user) {
            return $this->responseUnauthenticated($message);
        }
        if (!Hash::check($fields['password'], $user->password)) {
            return $this->responseUnauthenticated($message);
        }

        $token = $user->createToken($this->tokenName);

        return $this->response(
            [
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token->plainTextToken,
                ]
            ],
            201
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->responseMessage('Logout.');
    }
}
