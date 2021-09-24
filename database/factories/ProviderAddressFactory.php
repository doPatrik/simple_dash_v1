<?php

namespace Database\Factories;

use App\Models\ProviderAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProviderAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'postal_code' => $this->faker->postcode,
            'city' => $this->faker->city,
            'street_name' => $this->faker->streetAddress
        ];
    }
}
