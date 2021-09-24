<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'post_code' => $this->faker->postcode,
            'city' => $this->faker->city,
            'street_name' => $this->faker->streetAddress,
            'id_user' => User::query()->where('name', 'teszt')->first()->id,
        ];
    }
}
