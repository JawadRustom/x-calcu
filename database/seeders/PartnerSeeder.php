<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Partner::create([
            'name' => 'ابو الحسن',
            'phone' => '963 987 372 763',
            'email' => 'jawad.ru1@gmail.com',
            'user_id' => '1',
        ]);
        Partner::create([
            'name' => 'ابو الحسين',
            'phone' => '963 987 372 763',
            'email' => 'jawad.ru12@gmail.com',
            'user_id' => '1',
        ]);

//        \App\Models\Partner::factory(10)->create();
    }
}
