<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $avatars = [
            '/storage/images/IMG_3001.jpg',
            '/storage/images/IMG_6854.jpeg',
            '/storage/images/IMG_7991.jpeg',
            '/storage/images/IMG_20211012_183846_655.jpg',
        ];

        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => $this->faker->password,
            'api_token' => Str::random(60),
            'avatar' => $this->faker->randomElement($avatars),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
