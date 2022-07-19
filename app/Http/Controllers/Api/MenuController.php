<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MenuResource;
use App\Services\MenuService;
use Illuminate\Http\Request;

class MenuController extends ApiController
{
    protected MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index()
    {
        return MenuResource::collection($this->menuService->getAllMenus());
    }

    public function store(Request $request)
    {
        return new MenuResource($this->menuService->createMenuFromRequest($request));
    }

    public function show($id)
    {
        $menu = $this->menuService->getMenuByIdWithMenuItems($id);
        return $menu ? new MenuResource($menu) : $this->responseNotFound();
    }

    public function update($id, Request $request)
    {
        $menu = $this->menuService->updateMenuByIdFromRequest($id, $request);
        return $menu ? new MenuResource($menu) : $this->responseNotFound();
    }

    public function destroy($id)
    {
        return $this->menuService->destroyMenuById($id) ? $this->responseMessage('Destroyed.') : $this->responseNotFound();
    }
}
