<?php

namespace Database\Seeders;

use App\Models\product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        product::insert([
            ['name' => 'iPhone 14', 'price' => 59999, 'stock_quantity' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Samsung S23', 'price' => 49999, 'stock_quantity' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'OnePlus 11', 'price' => 42999, 'stock_quantity' => 20, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
