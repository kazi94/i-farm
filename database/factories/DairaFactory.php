<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Daira;
use App\Models\Wilaya;

class DairaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Daira::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'name_ar' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'wilaya_id' => Wilaya::factory(),
        ];
    }
}
