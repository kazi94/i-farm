<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Commune;
use App\Models\Daira;

class CommuneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Commune::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'name_ar' => $this->faker->regexify('[A-Za-z0-9]{200}'),
            'daira_id:' => Daira::factory()->create()->id:,
            'daira_id' => Daira::factory(),
        ];
    }
}
