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
        PaidBill::create([
            'invoice_date' => now(),
            'invoice_value' => 9000,
            'operation_id' => 1,
        ]);

        PaidBill::create([
            'invoice_date' => now()->addDays(10),
            'invoice_value' => 18000,
            'operation_id' => 2,
        ]);

        PaidBill::create([
            'invoice_date' => now(),
            'invoice_value' => 900,
            'operation_id' => 3,
        ]);

        PaidBill::create([
            'invoice_date' => now()->addDays(10),
            'invoice_value' => 1800,
            'operation_id' => 4,
        ]);

//        PaidBill::factory(10)->create();
    }
}
