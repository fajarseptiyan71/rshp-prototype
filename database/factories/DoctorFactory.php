<?php

namespace Database\Factories;

use App\Models\Specialist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specialistId = Specialist::query()->pluck('id');

        return [
            'specialist_id' => fake()->randomElement($specialistId),
            'name' => fake()->name(),
            'birth_place' => fake()->city(),
            'birth_date' => fake()->dateTimeBetween('-50 years', '-30 years'),
            'address' => fake()->address(),
            'gender'  => fake()->randomElement(['P', 'L'])
        ];
    }
}
