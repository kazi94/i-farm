<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Farm;
use App\Models\Farmer;
use App\Models\Preconisation;
use App\Models\User;

class PreconisationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Preconisation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->regexify('[A-Za-z0-9]{15}'),
            'note' => $this->faker->regexify('[A-Za-z0-9]{500}'),
            'farmer_id' => Farmer::factory(),
            'farm_id' => Farm::factory(),
            'created_by' => User::factory()->create()->created_by,
            'updated_by' => User::factory()->create()->updated_by,
            'deleted_at' => $this->faker->dateTime(),
            'user_id' => User::factory(),
        ];
    }
}
