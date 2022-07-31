<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\FileResource;
use App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends ApiController
{
    protected FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index()
    {
        return FileResource::collection($this->fileService->getAll());
    }

    public function store(Request $request)
    {
        return new FileResource($this->fileService->uploadFromRequest($request));
    }

    public function show($id)
    {
        $option = $this->fileService->getById($id);
        return $option ? new FileResource($option) : $this->responseNotFound();
    }

    public function showByName($name)
    {
        $option = $this->fileService->getByName($name);
        return $option ? new FileResource($option) : $this->responseNotFound();
    }

    public function destroy($id)
    {
        return $this->fileService->destroyById($id) ? $this->responseMessage('Destroyed') : $this->responseNotFound();
    }
}
