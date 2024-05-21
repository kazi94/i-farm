<?php

namespace Database\Seeders;

use App\Models\IntrantCategory;
use Illuminate\Database\Seeder;

class IntrantCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IntrantCategory::create([
            'name' => 'produits phytologiques',
        ])->intrantSousCategories()->createMany([
                    [
                        'name' => 'insecticides',
                    ],
                    [
                        'name' => 'acaricides',
                    ],
                    [
                        'name' => 'herbicides',
                    ],
                    [
                        'name' => 'fongicides',
                    ],
                    [
                        'name' => 'nematicides',
                    ],
                    [
                        'name' => 'limaticides',
                    ],
                    [
                        'name' => 'rodenticides',
                    ]
                ]);

        IntrantCategory::create([
            'name' => 'engrais',
        ])->intrantSousCategories()->createMany([
                    [
                        'name' => 'stimulants',
                    ]
                ]);
        IntrantCategory::create([
            'name' => 'divers',
        ])->intrantSousCategories()->createMany([
                    [
                        'name' => 'adjuvants',
                    ],
                    [
                        'name' => 'autres',
                    ]
                ]);
        IntrantCategory::create([
            'name' => 'amendements',
        ]);
    }
}
