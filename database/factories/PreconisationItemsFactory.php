<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Intrant;
use App\Models\Preconisation;
use App\Models\PreconisationItems;
use App\Models\Unit;

class PreconisationItemsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PreconisationItems::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'intrant_id' => Intrant::factory(),
            'quantity' => $this->faker->numberBetween(-10000, 10000),
            'price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'unit_id' => Unit::factory(),
            'preconisation_id' => Preconisation::factory(),
            'note' => $this->faker->regexify('[A-Za-z0-9]{100}'),
        ];
    }
}
