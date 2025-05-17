<?php

namespace Database\Seeders;

use App\Models\BufferStock;
use App\Models\Item;
use App\Models\ItemGroup;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');
        $groupIds = ItemGroup::pluck('id')->toArray();
        
        $units = ['قطعة', 'صندوق', 'علبة', 'طقم', 'كيلو', 'متر'];
        
        foreach(range(1, 100) as $index) {
            Item::create([
                'name' => $faker->unique()->word(),
                'description' => $faker->sentence(),
                'item_group_id' => $faker->randomElement($groupIds),
                'unit' => $faker->randomElement($units),
                'active' => true,
            ]);
            BufferStock::create([
                'item_id' => Item::latest()->first()->id,
                'quantity' => 0,
            ]);
        }
    }
}
