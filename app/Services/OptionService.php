<?php

namespace App\Services;

use App\Repositories\OptionRepository;
use Illuminate\Http\Request;

class OptionService
{
    protected $optionRepository;

    public function __construct(OptionRepository $optionRepository)
    {
        $this->optionRepository = $optionRepository;
    }

    public function getAllOptions()
    {
        return $this->optionRepository->all();
    }

    public function createNewOption(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'value' => 'required|string',
        ]);

        return $this->optionRepository->create($fields);
    }

    public function getOptionById($id)
    {
        return $this->optionRepository->find($id);
    }

    public function updateOptionById(Request $request, $id)
    {
        $fields = $request->validate([
            'name' => 'string',
            'value' => 'string',
        ]);

        return $this->optionRepository->update($id, $fields);
    }

    public function destroyOptionById($id)
    {
        return $this->optionRepository->destroy($id);
    }
}
