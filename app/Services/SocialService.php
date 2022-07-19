<?php

namespace App\Services;

use App\Repositories\SocialRepository;

class SocialService extends BaseService
{
    protected $createRules = [
        'name' => 'required|string',
        'icon' => 'required|string',
        'href' => 'required|string',
    ];
    protected $updateRules = [
        'name' => 'string',
        'icon' => 'string',
        'href' => 'string',
    ];

    public function __construct(SocialRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getByName($name)
    {
        return $this->repository->findByName($name);
    }
}
