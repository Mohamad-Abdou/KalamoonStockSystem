<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Item;
use App\Models\Items_group;
use Illuminate\Database\Eloquent\Collection;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ],[
                'name' => 'Stock Holder',
                'email' => 'StockHolder@uok.edu.sy',
                'password' => '12345678',
                'role' => 'أمين المستودع',
                'office_number' => '1234',
                'type' => '1'
            ],[
                'name' => 'Ameen',
                'email' => 'Ameen@uok.edu.sy',
                'password' => '12345678',
                'role' => 'أمين الجامعة',
                'office_number' => '1234',
                'type' => '2'
            ],[
                'name' => 'financualManager',
                'email' => 'financial@uok.edu.sy',
                'password' => '12345678',
                'role' => 'المدير المالي',
                'office_number' => '1234',
                'type' => '3'
            ],[
                'name' => 'IT Manager',
                'email' => 'ITmanager@uok.edu.sy',
                'password' => '12345678',
                'role' => 'مدير النظم',
                'office_number' => '1234',
                'type' => '4'
            ],]);
        Items_group::factory()->createMany([
            ['name' => 'طبي'],
            ['name' => 'قرطاسية'],
            ['name' => 'منظفات'],
        ]);
        Item::factory(10)->create();
    }
}
