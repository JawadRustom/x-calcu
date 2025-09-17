<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ]);
        User::factory()->create([
            'name' => 'Jawad',
            'email' => 'jawad@user.com',
        ]);
        User::factory()->create([
            'name' => 'Amjad',
            'email' => 'amjad@user.com',
        ]);
        User::factory()->create([
            'name' => 'Jafar',
            'email' => 'jafar@user.com',
        ]);
        User::factory()->create([
            'name' => 'Ayham',
            'email' => 'ayham@user.com',
        ]);
        User::factory()->create([
            'name' => 'Test 1',
            'email' => 'test1@user.com',
        ]);
        User::factory()->create([
            'name' => 'Test 2',
            'email' => 'test2@user.com',
        ]);
//        User::factory(4)->create();
    }
}
