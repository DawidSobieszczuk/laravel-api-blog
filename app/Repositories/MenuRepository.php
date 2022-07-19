<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Models\MenuItem;

class MenuRepository extends Repository
{
    public function __construct(Menu $menu)
    {
        $this->model = $menu;
    }

    public function find($id)
    {
        return $this->model->with('menuItems')->find($id);
    }

    public function findByName($name)
    {
        return $this->model->with('menuItems')->where('name', $name)->first();
    }
}
