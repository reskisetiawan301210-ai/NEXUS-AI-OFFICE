<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
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
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'has_lifehub' => true,
            'has_kasira' => true,
        ];
    }

    /**
     * Indicate that the user only has LifeHub AI.
     */
    public function lifehubOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'has_lifehub' => true,
            'has_kasira' => false,
            'product' => 'lifehub',
        ]);
    }

    /**
     * Indicate that the user only has Kasira.
     */
    public function kasiraOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'has_lifehub' => false,
            'has_kasira' => true,
            'product' => 'kasira',
        ]);
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
