<?php

namespace Database\Factories;

use App\Models\DefaultMenu;
use Illuminate\Database\Eloquent\Factories\Factory;

class DefaultMenuFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DefaultMenu::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $icons = collect([
          'fas fa-monument',
          'fas fa-industry',
          'far fa-building',
          'fas fa-clipboard',
        ]);

        return [
            'name' => $this->faker->name,
            'icon' => $icons->random(),
        ];
    }
}
