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
            AttendanceRecord::STATUS_LEAVE,
        ]);

        $date = fake()->dateTimeBetween('-2 months', 'now');
        $checkIn = null;
        $checkOut = null;
        $lateMinutes = 0;
        $notes = null;
        $leaveReason = null;

        if ($status === AttendanceRecord::STATUS_PRESENT) {
            $scheduledStart = Carbon::createFromTime(8, 0);
            $actualStart = (clone $scheduledStart)->addMinutes(fake()->numberBetween(0, 90));

            $checkIn = $actualStart->format('H:i');
            $checkOut = $actualStart->copy()->addHours(8)->format('H:i');
            $lateMinutes = max(0, $actualStart->diffInMinutes($scheduledStart));
            $notes = $lateMinutes > 5 ? 'Terlambat' : 'Tepat waktu';
            if ($lateMinutes > 5) {
                $status = AttendanceRecord::STATUS_PRESENT;
            }
        } else {
            $leaveReason = fake()->randomElement(array_keys(AttendanceRecord::leaveReasonOptions()));
            $notes = AttendanceRecord::leaveReasonOptions()[$leaveReason];
        }

        return [
            'employee_id' => Employee::factory(),
            'attendance_date' => $date,
            'status' => $status,
            'leave_reason' => $leaveReason,
            'check_in_time' => $checkIn,
            'check_out_time' => $checkOut,
            'late_minutes' => $lateMinutes,
            'notes' => $notes,
            'supporting_document_path' => null,
        ];
    }
}
