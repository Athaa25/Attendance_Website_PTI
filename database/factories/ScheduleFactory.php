<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->randomElement(['06:00', '08:00', '09:00', '14:00']);
        $end = Carbon::createFromFormat('H:i', $start)->addHours(8)->format('H:i');
        $shiftCode = fake()->unique()->randomElement(['A', 'B', 'C', 'D', 'E']);

        return [
            'code' => sprintf('SHIFT-%s', $shiftCode),
            'name' => sprintf('Shift %s', Str::upper($shiftCode)),
            'start_time' => $start,
            'end_time' => $end,
            'description' => fake()->sentence(),
        ];
    }
}
