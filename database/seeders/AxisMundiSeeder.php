<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Services\ArticleService;
use App\Services\MenuService;
use App\Services\OptionService;
use App\Services\SocialService;
use App\Services\UserService;
use Illuminate\Database\Seeder;

class AxisMundiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(SocialService $socialService, UserService $userService, MenuService $menuService, ArticleService $articleService, OptionService $optionService)
    {
        // User
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
        $user = $userService->create([
            'name' => 'admin',
            'email' => 'admin@ds.ds',
            'password' => 'pass',
        ])->assignRole('writer', 'editor', 'admin');

        // Article
        Article::factory()->count(100)->create();

        // Option
        $optionService->create([
            'name' => 'logo',
            'value' => 'ng/assets/logo.png',
        ]);
        $optionService->create([
            'name' => 'copyright',
            'value' => 'AxisMundi © 2023',
        ]);
        $optionService->create([
            'name' => 'hero',
            'value' => 'ng/assets/hero.png',
        ]);

        // Social
        $socialService->create([
            'name' => 'facebook',
            'icon' => 'fa-brands fa-facebook',
            'href' => '#',
        ]);
        $socialService->create([
            'name' => 'youtube',
            'icon' => 'fa-brands fa-youtube',
            'href' => '#',
        ]);
        $socialService->create([
            'name' => 'twiter',
            'icon' => 'fa-brands fa-twitter',
            'href' => '#',
        ]);

        // Menu
        $menuService->createMenu([
            'name' => 'nav'
        ]);
        $menuService->createMenuItem([
            'menu_id' => 1,
            'name' => 'Kategoria I',
            'href' => '#',
        ]);
        $menuService->createMenuItem([
            'menu_id' => 1,
            'name' => 'Kategoria II',
            'href' => '#',
        ]);
        $menuService->createMenuItem([
            'menu_id' => 1,
            'name' => 'Kategoria II',
            'href' => '#',
        ]);
    }
}
