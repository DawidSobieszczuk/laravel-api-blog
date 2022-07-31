<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Option;
use App\Services\MenuService;
use App\Services\SocialService;
use App\Services\UserService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DebugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(SocialService $socialService, UserService $userService, MenuService $menuService)
    {
        $userService->create([
            'name' => 'writer',
            'email' => 'writer@ds.ds',
            'password' => 'pass',
        ])->assignRole('writer');
        $userService->create([
            'name' => 'editor',
            'email' => 'editor@ds.ds',
            'password' => 'pass',
        ])->assignRole('writer', 'editor');
        $userService->create([
            'name' => 'admin',
            'email' => 'admin@ds.ds',
            'password' => 'pass',
        ])->assignRole('writer', 'editor', 'admin');

        Option::factory()->count(10)->create();
        Article::factory()->count(100)->create();

        $socialService->create([
            'name' => 'facebook',
            'icon' => 'fa-brands fa-facebook',
            'href' => '#',
        ]);
        $socialService->create([
            'name' => 'youtube',
            'icon' => 'fa-brands fa-youtub',
            'href' => '#',
        ]);
        $socialService->create([
            'name' => 'twiter',
            'icon' => 'fa-brands fa-twitter',
            'href' => '#',
        ]);

        $menuService->createMenu([
            'name' => 'nav'
        ]);
        $menuService->createMenuItem([
            'menu_id' => 1,
            'name' => 'One',
            'href' => '#',
        ]);
        $menuService->createMenuItem([
            'menu_id' => 1,
            'name' => 'Two',
            'href' => '#',
        ]);
        $menuService->createMenuItem([
            'menu_id' => 1,
            'name' => 'Three',
            'href' => '#',
        ]);
    }
}
