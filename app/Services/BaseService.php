<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BaseService
{
    protected $createRules = [];
    protected $updateRules = [];

    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function createFromRequest(Request $request)
    {
        $fileds = $request->validate($this->createRules);
        return $this->repository->create($fileds);
    }

    public function create($input)
    {
        $input = Validator::validate($input, $this->createRules);
        return $this->repository->create($input);
    }

    public function getById($id)
    {
        return $this->repository->find($id);
    }

    public function updateByIdFromRequest($id, Request $request)
    {
        $fileds = $request->validate($this->updateRules);
        return $this->repository->update($id, $fileds);
    }

    public function updateById($id, $input)
    {
        $input = Validator::validate($input, $this->updateRules);
        return $this->repository->update($id, $input);
    }

    public function destroyById($id)
    {
        return $this->repository->destroy($id);
    }
}
