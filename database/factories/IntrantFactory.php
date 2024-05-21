<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Distributor;
use App\Models\Firm;
use App\Models\Intrant;
use App\Models\SousCategory;

class IntrantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Intrant::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name_fr' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'name_ar' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'formulation' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'homologation_number' => $this->faker->regexify('[A-Za-z0-9]{30}'),
            'firm_id' => Firm::factory(),
            'sous_category_id' => SousCategory::factory(),
            'distributor_id' => Distributor::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
