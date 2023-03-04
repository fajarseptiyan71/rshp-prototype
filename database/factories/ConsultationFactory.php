<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Consultation>
 */
class ConsultationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $animals = ['kucing', 'anjing', 'ikan', 'sapi', 'kambing', 'kuda', 'kura-kura', 'kelinci'];
        $userId = User::query()->pluck('id');
        $doctorId = Doctor::query()->pluck('id');

        return [
            'user_id' => fake()->randomElement($userId),
            'doctor_id' => fake()->randomElement($doctorId),
            'animal_type' => fake()->randomElement($animals),
            'date' => fake()->dateTime()
        ];
    }
}
