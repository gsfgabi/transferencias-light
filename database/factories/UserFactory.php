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
            'document' => fake()->unique()->numerify('###########'), // CPF
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

    /**
     * Create a common user.
     */
    public function common(): static
    {
        return $this->afterCreating(function ($user) {
            $user->assignRole('common-user');
        });
    }

    /**
     * Create a merchant user.
     */
    public function merchant(): static
    {
        return $this->state(fn (array $attributes) => [
            'document' => fake()->unique()->numerify('##############'), // CNPJ
        ])->afterCreating(function ($user) {
            $user->assignRole('merchant');
        });
    }

    /**
     * Create an admin user.
     */
    public function admin(): static
    {
        return $this->afterCreating(function ($user) {
            $user->assignRole('admin');
        });
    }

    /**
     * Create a support user.
     */
    public function support(): static
    {
        return $this->afterCreating(function ($user) {
            $user->assignRole('support');
        });
    }

    /**
     * Create a basic user.
     */
    public function basic(): static
    {
        return $this->afterCreating(function ($user) {
            $user->assignRole('user');
        });
    }
}
