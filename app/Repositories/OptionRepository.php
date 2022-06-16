<?php

namespace App\Repositories;

use App\Models\Option;

class OptionRepository
{
    protected $option;

    public function __construct(Option $option)
    {
        $this->option = $option;
    }
}
