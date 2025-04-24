<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ContactListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contact_name' => fake()->name(),
            'total_sip' => fake()->numberBetween(1, 100),
            'family_org_name' => fake()->company(),
            'Pan_card' => strtoupper(Str::random(6)),
            'investment' => fake()->numberBetween(1000, 100000),
            'total_investment' => fake()->numberBetween(1000, 100000),
            'kyc_status' => fake()->randomElement(['complete', 'reject', 'progress', 'incomplete']),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('##########'),
            'Aadhar_card' => fake()->numerify('################'),
            'Rms' => fake()->name(),
            'gender' =>fake()->randomElement(['male', 'female', 'other']),
            'birthdate' =>fake()->date($format = 'Y-m-d', $max = 'now'),
            'relation' =>fake()->randomElement(['parent', 'sibling', 'friend', 'partner']),
        ];
    }
}
