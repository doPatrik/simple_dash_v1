<?php

namespace Database\Factories;

use App\Models\ExpenditureType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenditureTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExpenditureType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'color_code' => $this->faker->hexColor,
            'id_user' => 2,
        ];
    }
}
