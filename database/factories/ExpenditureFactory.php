<?php

namespace Database\Factories;

use App\Models\Expenditure;
use App\Models\ExpenditureType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenditureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Expenditure::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $types = ExpenditureType::query()->where('id_user', 2)->pluck('id_type');
        $date = $this->faker->dateTimeBetween('-120 days', '+90 days');
        return [
            'id_type' => $types->random(),
            'price' => $this->faker->numberBetween(1000, 999999),
            'description' => $this->faker->word,
            'id_user' => 2,
            'purchase_date' => $date,
        ];
    }
}
