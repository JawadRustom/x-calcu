<?php

namespace Database\Seeders;

use Ejarnutowski\LaravelApiKey\Models\ApiKey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ApiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the key already exists (optional, for idempotency)
        if (!config('app.api_key')) {  // Adjust this based on how you store the API key
//            Artisan::call('apikey:generate', [
//                'name' => 'key1',  // Adjust based on your command signature
//            ]);
            ApiKey::create([
                'name' => 'key1',
                'key'  => 'AIOEnOSLmuellmwKzSPfG794LCfT4cskhTgi9KS1RhsmkkCGwMD6FtRI1eXUKCS9' // Auto-generates a key if not provided
            ]);
        }
    }
}
