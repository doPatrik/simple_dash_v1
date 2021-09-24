<?php

namespace Database\Factories;

use App\Models\NewBill;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class NewBillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NewBill::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        /** @var User $tesztUser */
        $tesztUser = User::query()->where('name', 'teszt')->first();
        $providers = $tesztUser->providers()->get()->pluck('id_provider');
        $startDate = $this->faker->dateTimeBetween('-120 days', '+90 days');
        return [
            'start_date' => $startDate,
            'end_date' => Carbon::parse($startDate)->copy()->addDays(30)->format('Y-m-d'),
            'price' => $this->faker->numberBetween(1000, 999999),
            'dead_line' => Carbon::parse($startDate)->copy()->addDays(36)->format('Y-m-d'),
            'is_paid' => $this->faker->randomElement([0,1,1,1]),
            'id_provider' => $providers->random(),
            'id_user' => $tesztUser->id,
        ];
    }
}
