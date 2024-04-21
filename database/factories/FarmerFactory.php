<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Commune;
use App\Models\Daira;
use App\Models\Farm;
use App\Models\Farmer;
use App\Models\User;
use App\Models\Wilaya;

class FarmerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Farmer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'fullname' => $this->faker->regexify('[A-Za-z0-9]{200}'),
            'address' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'phone1' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'phone2' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'website' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'facebook_url' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'twitter_url' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'instagram_url' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'linkedin_url' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'image_url' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'email' => $this->faker->safeEmail(),
            'note' => $this->faker->regexify('[A-Za-z0-9]{500}'),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'activity' => $this->faker->randomElement(["culture", "culture_livestock"]),
            'status' => $this->faker->randomElement(["silver", "bronze", "gold"]),
            'commune_id' => Commune::first()->get('id'),
            'daira_id' => Daira::first()->get('id'),
            'wilaya_id' => Wilaya::first()->get('id'),
            'created_by' => User::first()->get('id'),
            'updated_by' => User::first()->get('id'),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
            'deleted_at' => $this->faker->dateTime(),
        ];
    }
}
