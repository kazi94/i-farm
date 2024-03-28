<?php

namespace Database\Seeders;

use App\Models\Farmer;
use Illuminate\Database\Seeder;

class FarmerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Farmer::factory()->count(5)->create();
    }
}
