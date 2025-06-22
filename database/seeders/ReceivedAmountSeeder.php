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
        ReceivedAmount::create([
            'invoice_date' => now(),
            'invoice_value' => 4000,
            'operation_id' => 1,
        ]);

        ReceivedAmount::create([
            'invoice_date' => now()->addDays(10),
            'invoice_value' => 4000,
            'operation_id' => 1,
        ]);

        ReceivedAmount::create([
            'invoice_date' => now(),
            'invoice_value' => 8000,
            'operation_id' => 2,
        ]);

        ReceivedAmount::create([
            'invoice_date' => now()->addDays(10),
            'invoice_value' => 8000,
            'operation_id' => 2,
        ]);

        ReceivedAmount::create([
            'invoice_date' => now(),
            'invoice_value' => 400,
            'operation_id' => 3,
        ]);

        ReceivedAmount::create([
            'invoice_date' => now()->addDays(10),
            'invoice_value' => 400,
            'operation_id' => 3,
        ]);

        ReceivedAmount::create([
            'invoice_date' => now(),
            'invoice_value' => 800,
            'operation_id' => 4,
        ]);

        ReceivedAmount::create([
            'invoice_date' => now()->addDays(10),
            'invoice_value' => 800,
            'operation_id' => 4,
        ]);

//        ReceivedAmount::factory(10)->create();
    }
}
