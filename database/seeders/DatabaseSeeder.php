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
        User::factory()->createMany([
            [
                'name' => 'administrator',
                'email' => 'administrator@uok.edu.sy',
                'password' => '12345678',
                'role' => 'مدير النظام',
                'office_number' => '1234',
                'type' => '0'
            ],
            [
                'name' => 'Stock Holder',
                'email' => 'StockHolder@uok.edu.sy',
                'password' => '12345678',
                'role' => 'أمين المستودع',
                'office_number' => '1234',
                'type' => '1'
            ],
            [
                'name' => 'Ameen',
                'email' => 'Ameen@uok.edu.sy',
                'password' => '12345678',
                'role' => 'أمين الجامعة',
                'office_number' => '1234',
                'type' => '2'
            ],
            [
                'name' => 'financualManager',
                'email' => 'financial@uok.edu.sy',
                'password' => '12345678',
                'role' => 'المدير المالي',
                'office_number' => '1234',
                'type' => '3'
            ],
            [
                'name' => 'IT Manager',
                'email' => 'ITmanager@uok.edu.sy',
                'password' => '12345678',
                'role' => 'مدير النظم',
                'office_number' => '1234',
                'type' => '4'
            ],
        ]);

        ItemGroup::factory()->createMany([
            ['name' => 'طبي'],
            ['name' => 'قرطاسية'],
            ['name' => 'منظفات'],
        ]);

        RequestFlow::factory()->createMany([
            [
                'user_id' => 3,
                'request_type' => 0,
                'order' => 1,
            ],
            [
                'user_id' => 4,
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
            ]
        ]);
    }
}
