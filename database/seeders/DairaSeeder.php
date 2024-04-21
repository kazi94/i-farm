<?php

namespace Database\Seeders;

use App\Models\Farmer;
use Illuminate\Database\Seeder;

class DairaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file_path = base_path('database\data\dairas.sql');

        \DB::unprepared(
            file_get_contents($file_path)
        );
    }
}
