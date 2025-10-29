<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schedules = [
            ['code' => 'SHIFT-1', 'name' => 'Shift 1', 'start_time' => '06:00', 'end_time' => '14:00', 'description' => 'Shift pagi'],
            ['code' => 'SHIFT-2', 'name' => 'Shift 2', 'start_time' => '08:00', 'end_time' => '16:00', 'description' => 'Shift reguler'],
            ['code' => 'SHIFT-3', 'name' => 'Shift 3', 'start_time' => '14:00', 'end_time' => '22:00', 'description' => 'Shift sore'],
        ];

        foreach ($schedules as $schedule) {
            Schedule::updateOrCreate(
                ['code' => $schedule['code']],
                [
                    'name' => $schedule['name'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                    'description' => $schedule['description'],
                ]
            );
        }
    }
}
