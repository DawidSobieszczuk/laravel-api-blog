<?php

namespace App\Repositories;

use App\Models\Social;

class SocialRepository extends Repository
{
    public function __construct(Social $social)
    {
        $this->model = $social;
    }

    public function findByName(string $name): Social
    {
        return $this->model->where('name', $name)->first();
    }
}
