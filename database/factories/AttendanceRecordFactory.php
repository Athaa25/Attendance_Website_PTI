<?php

namespace Database\Factories;

use App\Models\AttendanceReason;
use App\Models\AttendanceRecord;
use App\Models\AttendanceStatus;
use App\Models\Employee;
use App\Models\PresenceTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $statusCode = fake()->randomElement([
            AttendanceRecord::STATUS_PRESENT,
            AttendanceRecord::STATUS_LEAVE,
            AttendanceRecord::STATUS_SICK,
        ]);

        $statusModel = AttendanceStatus::query()->firstOrCreate(
            ['code' => $statusCode],
            [
                'label' => ucfirst($statusCode),
                'description' => ucfirst($statusCode),
                'requires_reason' => in_array($statusCode, [
                    AttendanceRecord::STATUS_LEAVE,
                    AttendanceRecord::STATUS_SICK,
                ], true),
            ]
        );

        $date = Carbon::instance(fake()->dateTimeBetween('-2 months', 'now'))->startOfDay();
        $checkIn = null;
        $checkOut = null;
        $lateMinutes = 0;
        $notes = null;
        $leaveReason = null;
        $reasonModel = null;

        if ($statusCode === AttendanceRecord::STATUS_PRESENT) {
            $scheduledStart = Carbon::createFromTime(8, 0);
            $actualStart = (clone $scheduledStart)->addMinutes(fake()->numberBetween(0, 90));

            $checkIn = $actualStart->format('H:i');
            $checkOut = $actualStart->copy()->addHours(8)->format('H:i');
            $lateMinutes = max(0, $actualStart->diffInMinutes($scheduledStart));
            $notes = $lateMinutes > 5 ? 'Terlambat' : 'Tepat waktu';
        } else {
            $leaveReason = fake()->randomElement(array_keys(AttendanceRecord::leaveReasonOptions()));
            $reasonModel = AttendanceReason::query()->firstOrCreate(
                ['code' => $leaveReason],
                ['label' => AttendanceRecord::leaveReasonOptions()[$leaveReason] ?? ucfirst($leaveReason)]
            );
            $notes = $reasonModel->label;
        }

        return [
            'employee_id' => Employee::factory(),
            'attendance_date' => $date,
            'status' => $statusCode,
            'status_id' => $statusModel->id,
            'leave_reason' => $leaveReason,
            'reason_id' => $reasonModel?->id,
            'check_in_time' => $checkIn,
            'check_out_time' => $checkOut,
            'late_minutes' => $lateMinutes,
            'notes' => $notes,
            'supporting_document_path' => null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (AttendanceRecord $record) {
            $attendanceDate = $record->attendance_date instanceof Carbon
                ? $record->attendance_date
                : Carbon::parse($record->attendance_date);

            $updates = [];

            if ($record->check_in_time && ! $record->check_in_time_id) {
                $presence = PresenceTime::create([
                    'employee_id' => $record->employee_id,
                    'type' => PresenceTime::TYPE_CHECK_IN,
                    'checkin_time' => $record->check_in_time,
                    'recorded_at' => Carbon::createFromFormat('Y-m-d H:i', "{$attendanceDate->format('Y-m-d')} {$record->check_in_time}"),
                ]);
                $updates['check_in_time_id'] = $presence->id;
            }

            if ($record->check_out_time && ! $record->check_out_time_id) {
                $presence = PresenceTime::create([
                    'employee_id' => $record->employee_id,
                    'type' => PresenceTime::TYPE_CHECK_OUT,
                    'checkout_time' => $record->check_out_time,
                    'recorded_at' => Carbon::createFromFormat('Y-m-d H:i', "{$attendanceDate->format('Y-m-d')} {$record->check_out_time}"),
                ]);
                $updates['check_out_time_id'] = $presence->id;
            }

            if (! empty($updates)) {
                $record->update($updates);
            }
        });
    }
}
