<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserService extends BaseService
{
    private $tokenName = 'TokenName';

    protected $createRules = [
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string',
    ];
    protected $updateRules = [
        'name' => 'string',
        'email' => 'email|unique:users,email',
        'password' => 'string|confirmed',
    ];

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getCurrentLoggedUser()
    {
        if (!auth('sanctum')->user()) return null;

        $id = auth('sanctum')->user()->id;
        return $this->repository->find($id);
    }

    public function updateCurrentLoggedUserFromRequest(Request $request)
    {
        if (!auth('sanctum')->user()) return null;

        $id = auth('sanctum')->user()->id;
        return $this->updateByIdFromRequest($id, $request);
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

        $user = $this->repository->findByEmail($fields['email']);

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

    public function getCurrentLoggedUserPermissions()
    {
        $user = $this->getCurrentLoggedUser();
        if (!$user) return [];

        return $user->getAllPermissions();
    }
}
