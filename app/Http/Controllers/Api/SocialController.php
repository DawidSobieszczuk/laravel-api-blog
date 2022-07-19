<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SocialResource;
use App\Services\SocialService;
use Illuminate\Http\Request;

class SocialController extends ApiController
{
    protected SocialService $socialService;

    public function __construct(SocialService $socialService)
    {
        $this->socialService = $socialService;
    }

    public function index()
    {
        return SocialResource::collection($this->socialService->getAll());
    }

    public function store(Request $request)
    {
        return new SocialResource($this->socialService->createFromRequest($request));
    }

    public function show($id)
    {
        $social = $this->socialService->getById($id);
        return $social ? new SocialResource($social) : $this->responseNotFound();
    }

    public function update($id, Request $request)
    {
        $social = $this->socialService->updateByIdFromRequest($id, $request);
        return $social ? new SocialResource($social) : $this->responseNotFound();
    }

    public function destroy($id)
    {
        return $this->socialService->destroyById($id) ? $this->responseMessage('Destroyed.') : $this->responseNotFound();
    }
}
