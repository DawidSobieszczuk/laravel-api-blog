<?php

namespace App\Repositories;

use App\Models\Option;

class OptionRepository extends Repository
{
    public function __construct(Option $option)
    {
        $this->model = $option;
    }
}
