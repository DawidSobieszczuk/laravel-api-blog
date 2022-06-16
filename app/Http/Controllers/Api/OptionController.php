<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OptionResource;
use App\Models\Option;
use App\Services\OptionService;
use Illuminate\Http\Request;

class OptionController extends ApiController
{
    protected OptionService $optionService;

    public function __construct(OptionService $optionService)
    {
        $this->optionService = $optionService;
    }

    public function index()
    {
        return OptionResource::collection($this->optionService->getAllOptions());
    }

    public function store(Request $request)
    {
        return new OptionResource($this->optionService->createNewOption($request));
    }

    public function show($id)
    {
        $option = $this->optionService->getOptionById($id);

        return $option ? new OptionResource($option) : $this->responseNotFound();
    }

    public function update(Request $request, $id)
    {
        $option = $this->optionService->updateOptionById($request, $id);

        return $option ? new OptionResource($option) : $this->responseNotFound();
    }

    public function destroy($id)
    {
        return $this->optionService->destroyOptionById($id) ? $this->responseMessage('Destroyed') : $this->responseNotFound();
    }
}
