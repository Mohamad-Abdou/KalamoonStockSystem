<?php

namespace Database\Seeders;

use App\Models\AppConfiguration;
use App\Models\ItemGroup;
use App\Models\User;
use App\Models\ItemsGroup;
use App\Models\RequestFlow;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ItemGroup::factory()->createMany([
            ['name' => 'طبي'],
            ['name' => 'قرطاسية'],
            ['name' => 'منظفات'],
        ]);

        RequestFlow::factory()->createMany([
            [
                'user_id' => 4,
                'request_type' => 0,
                'order' => 1,
            ],
            [
                'user_id' => 3,
                'request_type' => 0,
                'order' => 2,
            ],
            [
                'user_id' => 3,
                'request_type' => 1,
                'order' => 1,
            ],
        ]);

        AppConfiguration::factory()->CreateMany([
            [
                'name' => 'AnnualRequestPeriod',
                'key' => 'start',
                'value' => now()->toDateString(),
            ],[
                'name' => 'AnnualRequestPeriod',
                'key' => 'end',
                'value' => now()->addDays(10)->toDateString(),
            ],[
                'name' => 'LastReset',
                'key' => 'Date',
                'value' => now()->toDateString(),
            ],[
                'name' => 'Year',
                'key' => 'state',
                'value' => false,
            ],[
                'name' => 'Year',
                'key' => 'semester',
                'value' => 1,
            ]
        ]);
    }
}
