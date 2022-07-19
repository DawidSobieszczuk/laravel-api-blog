<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'create articles']);
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'publish articles']);
        Permission::create(['name' => 'unpublish articles']);

        Permission::create(['name' => 'create options']);
        Permission::create(['name' => 'edit options']);
        Permission::create(['name' => 'delete options']);

        Permission::create(['name' => 'create socials']);
        Permission::create(['name' => 'edit socials']);
        Permission::create(['name' => 'delete socials']);

        Permission::create(['name' => 'create menus']);
        Permission::create(['name' => 'edit menus']);
        Permission::create(['name' => 'delete menus']);

        // writer
        $writer = Role::create(['name' => 'writer']);
        $writer->givePermissionTo('create articles');
        $writer->givePermissionTo('edit articles');
        $writer->givePermissionTo('delete articles');

        // editor
        $editor = Role::create(['name' => 'editor']);
        $editor->givePermissionTo('publish articles');
        $editor->givePermissionTo('unpublish articles');

        // super-admin
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());
    }
}
