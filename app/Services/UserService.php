<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserService
{
    private $tokenName = 'TokenName';
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserById($id)
    {
        return $this->userRepository->find($id);
    }

    public function updateUserByIdFromRequest($id, Request $request)
    {
        $fields = $request->validate([
            'name' => 'string',
            'email' => 'email|unique:users,email',
            'password' => 'string|confirmed',
        ]);

        return $this->userRepository->update($id, $fields);
    }

    public function getCurrentLoggedUser()
    {
        $id = Auth::user()->id;
        return $this->getUserById($id);
    }

    public function updateCurrentLoggedUserFromRequest(Request $request)
    {
        $id = Auth::user()->id;
        return $this->updateUserByIdFromRequest($id, $request);
    }

    /**
     * Return json array or false
     *
     * @param Request $request
     * @return array|bool
     */
    public function createTokenFromRequest(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = $this->userRepository->findByEmail($fields['email']);

        // Check email and password
        if (!$user) return false;
        if (!Hash::check($fields['password'], $user->password)) return false;

        return array(
            'data' => [
                'user' => $user,
                'token' => $user->createToken($this->tokenName)->plainTextToken,
            ],
        );
    }

    public function createNewUser($name, $email, $password, $isAdmin)
    {
        $input['name'] = $name;
        $input['email'] = $email;
        $input['password'] = $password;
        $input['is_admin'] = $isAdmin;

        $input = Validator::validate($input, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'is_admin' => 'required|boolean',
        ]);

        return $this->userRepository->create($input);
    }

    public function updateUserById($id, $name = null, $email = null, $password = null, $isAdmin = null)
    {
        $input = array();
        if ($name) $input['name'] = $name;
        if ($email) $input['email'] = $email;
        if ($password) $input['password'] = $password;
        if ($isAdmin) $input['is_admin'] = $isAdmin;

        $input = Validator::validate($input, [
            'name' => 'string',
            'email' => 'email|unique:users,email',
            'password' => 'string',
            'is_admin' => 'boolean',
        ]);

        return $this->userRepository->update($id, $input);
    }
}
