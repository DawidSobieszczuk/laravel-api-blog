<?php

namespace App\Repositories;

use App\Models\MenuItem;

class MenuItemRepository extends Repository
{
    public function __construct(MenuItem $menu)
    {
        $this->model = $menu;
    }
}
