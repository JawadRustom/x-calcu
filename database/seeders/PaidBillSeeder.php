<?php

namespace Database\Seeders;

use App\Models\PaidBill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaidBillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaidBill::factory(10)->create();
    }
}
