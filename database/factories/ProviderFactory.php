<?php

namespace Database\Factories;

use App\Models\Provider;
use App\Models\ProviderAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Provider::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        /**  @var $tesztUser User */
        $tesztUser = User::query()->where('name', 'teszt')->first();
        $address = $tesztUser->addresses->pluck('id_address');
        return [
            'name' => $this->faker->company,
            'number' => $this->faker->unique()->numberBetween(100000,999999),
            'owner_name' => $this->faker->name,
            'label' => $this->faker->word,
            'color_code' => $this->faker->hexColor,
            'id_provider_address' => ProviderAddress::factory()->create(),
            'id_user' => $tesztUser->id,
            'id_address' => $address->random(),
        ];
    }
}
