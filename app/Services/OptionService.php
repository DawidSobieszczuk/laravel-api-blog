<?php

namespace App\Services;

use App\Repositories\OptionRepository;

class OptionService
{
    protected $optionRepository;

    public function __construct(OptionRepository $optionRepository)
    {
        $this->optionRepository = $optionRepository;
    }
}
