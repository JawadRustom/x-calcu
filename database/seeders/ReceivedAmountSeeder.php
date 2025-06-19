<?php

namespace Database\Seeders;

use App\Models\ReceivedAmount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReceivedAmountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReceivedAmount::factory(10)->create();
    }
}
