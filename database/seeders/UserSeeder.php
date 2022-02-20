<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'user',
            'email' => 'user@email.com',
            'password' => 'password',
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'admin',
            'email' => 'admin@email.com',
            'password' => 'password',
            'is_admin' => true,
        ]);
    }
}
