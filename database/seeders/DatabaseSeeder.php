<?php

namespace Database\Seeders;

use App\Enums\OperationTypeEnum;
use App\Models\Operation;
use App\Models\PaidBill;
use App\Models\Partner;
use App\Models\ReceivedAmount;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(
            [
                UserSeeder::class,
                PartnerSeeder::class,
                OperationSeeder::class,
                PaidBillSeeder::class,
                ReceivedAmountSeeder::class
            ]
        );
    }
}
