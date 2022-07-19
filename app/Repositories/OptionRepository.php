<?php

namespace App\Repositories;

use App\Models\Option;

class OptionRepository extends Repository
{
    public function __construct(Option $option)
    {
        $this->model = $option;
    }

    public function findByName(string $name): Option
    {
        return $this->model->where('name', $name)->first();
    }
}
