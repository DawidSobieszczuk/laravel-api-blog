<?php

namespace App\Services;

use App\Repositories\MenuRepository;
use App\Repositories\MenuItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuService
{
    protected MenuRepository $menuRepository;
    protected MenuItemRepository $menuItemRepository;

    protected $createMenuRules = [
        'name' => 'required|string',
    ];
    protected $updateMenuRules = [
        'name' => 'string',
    ];
    protected $createMenuItemRules = [
        'name' => 'required|string',
        'href' => 'required|string',
        'menu_id' => 'required|integer',
    ];
    protected $updateMenuItemRules = [
        'name' => 'string',
        'href' => 'string',
        'menu_id' => 'integer',

    ];

    public function __construct(MenuRepository $menuRepository, MenuItemRepository $menuItemRepository)
    {
        $this->menuRepository = $menuRepository;
        $this->menuItemRepository = $menuItemRepository;
    }

    public function getAllMenus()
    {
        return $this->menuRepository->all();
    }

    public function getAllMenuItems()
    {
        return $this->menuItemRepository->all();
    }

    public function createMenu($input)
    {
        $input = Validator::validate($input, $this->createMenuRules);
        return $this->menuRepository->create($input);
    }

    public function createMenuItem($input)
    {
        $input = Validator::validate($input, $this->createMenuItemRules);
        return $this->menuItemRepository->create($input);
    }

    public function createMenuFromRequest(Request $request)
    {
        $fields = $request->validate($this->createMenuRules);
        return $this->menuRepository->create($fields);
    }

    public function createMenuItemFromRequest(Request $request)
    {
        $fields = $request->validate($this->createMenuItemRules);
        return $this->menuItemRepository->create($fields);
    }

    public function getMenuByIdWithMenuItems($id)
    {
        return $this->menuRepository->find($id);
    }

    public function getMenuItemById($id)
    {
        return $this->menuItemRepository->find($id);
    }

    public function updateMenuById($id, $input)
    {
        $input = Validator::validate($input, $this->updateMenuRules);
        return $this->menuRepository->update($id, $input);
    }

    public function updateMenuItemById($id, $input)
    {
        $input = Validator::validate($input, $this->updateMenuItemRules);
        return $this->menuItemRepository->update($id, $input);
    }

    public function updateMenuByIdFromRequest($id, Request $request)
    {
        $fields = $request->validate($this->updateMenuRules);
        return $this->menuRepository->update($id, $fields);
    }

    public function updateMenuItemByIdFromRequest($id, Request $request)
    {
        $fields = $request->validate($this->updateMenuItemRules);
        return $this->menuItemRepository->update($id, $fields);
    }

    public function destroyMenuById($id)
    {
        return $this->menuRepository->destroy($id);
    }

    public function destroyMenuItemById($id)
    {
        return $this->menuItemRepository->destroy($id);
    }
}
