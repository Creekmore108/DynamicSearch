<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'age' => fake()->numberBetween(18, 99),
            'date_of_birth' => fake()->date(),
            'location' => fake()->city(),
            'distance' => fake()->numberBetween(0, 100),
            'sexual_preference' => fake()->randomElement(['straight', 'gay', 'bisexual','pansexual', 'asexual', 'queer', 'lesbian', 'questioning']),
            'relationship_type' => fake()->randomElement(['casual', 'serious', 'marriage', 'friendship', 'polyamorous', 'open']),
            'height' => fake()->numberBetween(150, 200),
            'body_type' => fake()->randomElement(['slim', 'average', 'athletic']),
            'wants_children' => fake()->randomElement(['yes', 'no']),
            'smoking' => fake()->randomElement(['yes', 'no']),
            'ethnicity' => fake()->randomElement(['white', 'black', 'asian']),
            'spiritual_beliefs' => fake()->randomElement(['christianity', 'islam', 'judaism']),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
