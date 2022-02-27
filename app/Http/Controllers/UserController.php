<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiController
{
    public function show()
    {
        return $this->response(Auth::user());
    }

    public function update(Request $request)
    {
        $fields = $request->validate([
            'name' => 'string',
            'email' => 'email|unique:users,email',
            'password' => 'string|confirmed',
        ]);

        /** @var User */
        $user = Auth::user();

        $user->update($fields);

        return $this->response($user);
    }
}
