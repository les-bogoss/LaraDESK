<?php

namespace Database\Factories;

use App\Models\Ticket_category;
use App\Models\Ticket_status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->realText(25),
            'priority' => $this->faker->numberBetween(0, 3),
            'rating' => $this->faker->numberBetween(0, 3),

            'user_id' => User::all()->random()->id,
            'category_id' => Ticket_category::all()->random()->id,
            'status_id' => Ticket_status::all()->random()->id,

            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
