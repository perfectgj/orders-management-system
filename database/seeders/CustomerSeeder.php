<?php

namespace Database\Seeders;

use App\Models\customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        customer::insert([
            ['name' => 'Girish Jadeja', 'email' => 'girish@example.com', 'phone' => '9876543210', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ravi Kumar', 'email' => 'ravi@example.com', 'phone' => '9123456780', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
