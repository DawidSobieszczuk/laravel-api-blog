<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OptionResource;
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
        return OptionResource::collection($this->optionService->getAll())->additional(['message' => 'All options loaded']);
    }

    public function store(Request $request)
    {
        return (new OptionResource($this->optionService->createFromRequest($request)))->additional(['message' => 'New option created']);
    }

    public function show($id)
    {
        $option = $this->optionService->getById($id);
        return $option ? (new OptionResource($option))->additional(['message' => "Option '$option->name' loaded"]) : $this->responseNotFound();
    }

    public function showByName($name)
    {
        $option = $this->optionService->getByName($name);
        return $option ? (new OptionResource($option))->additional(['message' => 'Get option']) : $this->responseNotFound();
    }

    public function update($id, Request $request)
    {
        $option = $this->optionService->updateByIdFromRequest($id, $request);

        return $option ? (new OptionResource($option))->additional(['message' => "Option '$option->name' updated"]) : $this->responseNotFound();
    }

    public function destroy($id)
    {
        return $this->optionService->destroyById($id) ? $this->responseMessage("Option '$option->name' destroyed") : $this->responseNotFound();
    }
}
