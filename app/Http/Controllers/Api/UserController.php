<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function show()
    {
        return new UserResource(auth('sanctum')->user());
    }

    public function update(Request $request)
    {
        $fields = $request->validate([
            'name' => 'string',
            'email' => 'email|unique:users,email',
            'password' => 'string|confirmed',
        ]);

        $user = $request->user();
        $user->update($fields);
        return new UserResource($user);
    }
}
