<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Farm;
use App\Models\Farmer;
use App\Models\User;

class FarmFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Farm::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'classification' => $this->faker->word(),
            'area' => $this->faker->randomFloat(0, 0, 9999999999.),
            'unit' => $this->faker->randomElement(["ha","meter"]),
            'famille' => $this->faker->regexify('[A-Za-z0-9]{200}'),
            'setting' => $this->faker->randomElement(["grassland","forest","water"]),
            'farm_type' => $this->faker->randomElement(["permanent","seasonal"]),
            'farm_use' => $this->faker->randomElement(["livestock","agriculture"]),
            'farmer_id' => Farmer::factory(),
            'created_by' => User::factory()->create()->created_by,
            'updated_by' => User::factory()->create()->updated_by,
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
            'deleted_at' => $this->faker->dateTime(),
        ];
    }
}
