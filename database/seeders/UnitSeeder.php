<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file_path = base_path('database\data\units.sql');

        \DB::unprepared(
            file_get_contents($file_path)
        );

    }
}
