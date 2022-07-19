<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MenuItemResource;
use App\Services\MenuService;
use Illuminate\Http\Request;

class MenuItemController extends ApiController
{
    protected MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index()
    {
        return MenuItemResource::collection($this->menuService->getAllMenuItems());
    }

    public function store(Request $request)
    {
        return new MenuItemResource($this->menuService->createMenuItemFromRequest($request));
    }

    public function show($id)
    {
        $menuItem = $this->menuService->getMenuItemById($id);
        return $menuItem ? new MenuItemResource($menuItem) : $this->responseNotFound();
    }

    public function update($id, Request $request)
    {
        $menuItem = $this->menuService->updateMenuItemByIdFromRequest($id, $request);
        return $menuItem ? new MenuItemResource($menuItem) : $this->responseNotFound();
    }

    public function destroy($id)
    {
        return $this->menuService->destroyMenuItemById($id) ? $this->responseMessage('Destroyed.') : $this->responseNotFound();
    }
}
