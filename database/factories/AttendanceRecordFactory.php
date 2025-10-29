<?php

namespace Database\Factories;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceRecord>
 */
class AttendanceRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement([
            AttendanceRecord::STATUS_PRESENT,
            AttendanceRecord::STATUS_LATE,
            AttendanceRecord::STATUS_LEAVE,
            AttendanceRecord::STATUS_SICK,
            AttendanceRecord::STATUS_ABSENT,
        ]);

        $date = fake()->dateTimeBetween('-2 months', 'now');
        $checkIn = null;
        $checkOut = null;
        $lateMinutes = 0;
        $notes = null;

        if (in_array($status, [AttendanceRecord::STATUS_PRESENT, AttendanceRecord::STATUS_LATE], true)) {
            $scheduledStart = Carbon::createFromTime(8, 0);
            $actualStart = (clone $scheduledStart)->addMinutes(fake()->numberBetween(0, 90));

            $checkIn = $actualStart->format('H:i');
            $checkOut = $actualStart->copy()->addHours(8)->format('H:i');
            $lateMinutes = max(0, $actualStart->diffInMinutes($scheduledStart));
            $notes = $lateMinutes > 0 ? 'Datang terlambat' : 'Tepat waktu';
            $status = $lateMinutes > 0 ? AttendanceRecord::STATUS_LATE : AttendanceRecord::STATUS_PRESENT;
        } elseif ($status === AttendanceRecord::STATUS_LEAVE) {
            $notes = 'Izin';
        } elseif ($status === AttendanceRecord::STATUS_SICK) {
            $notes = 'Sakit';
        } else {
            $notes = 'Alpa';
        }

        return [
            'employee_id' => Employee::factory(),
            'attendance_date' => $date,
            'status' => $status,
            'check_in_time' => $checkIn,
            'check_out_time' => $checkOut,
            'late_minutes' => $lateMinutes,
            'notes' => $notes,
        ];
    }
}
