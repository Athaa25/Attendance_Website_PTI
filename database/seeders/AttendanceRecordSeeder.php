<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::with('schedule')->get();
        $today = now()->startOfDay();
        $daysBack = 30;

        foreach ($employees as $employee) {
            for ($day = 0; $day < $daysBack; $day++) {
                $date = $today->copy()->subDays($day);

                // Skip Sunday to mimic non-working days
                if ($date->isSunday()) {
                    continue;
                }

                $existing = AttendanceRecord::where('employee_id', $employee->id)
                    ->whereDate('attendance_date', $date)
                    ->exists();

                if ($existing) {
                    continue;
                }

                $status = $this->determineStatus();
                $checkIn = null;
                $checkOut = null;
                $lateMinutes = 0;
                $notes = null;
                $leaveReason = null;

                if ($status === AttendanceRecord::STATUS_PRESENT) {
                    $scheduleStart = $employee->schedule?->start_time
                        ? Carbon::parse($employee->schedule->start_time)
                        : Carbon::createFromTime(8, 0);

                    $lateness = random_int(0, 15);

                    $actualStart = (clone $scheduleStart)->addMinutes($lateness);
                    $checkIn = $actualStart->format('H:i');
                    $checkOut = $actualStart->copy()->addHours(8)->format('H:i');
                    $lateMinutes = max(0, $actualStart->diffInMinutes($scheduleStart));
                    $notes = $lateMinutes > 5 ? 'Datang terlambat' : 'Tepat waktu';
                } else {
                    [$leaveReason, $notes] = $this->generateLeaveDetails();
                }

                AttendanceRecord::create([
                    'employee_id' => $employee->id,
                    'attendance_date' => $date,
                    'status' => $status,
                    'leave_reason' => $leaveReason,
                    'check_in_time' => $checkIn,
                    'check_out_time' => $checkOut,
                    'late_minutes' => $lateMinutes,
                    'notes' => $notes,
                    'supporting_document_path' => null,
                ]);
            }
        }
    }

    private function determineStatus(): string
    {
        $roll = random_int(1, 100);

        return match (true) {
            $roll <= 80 => AttendanceRecord::STATUS_PRESENT,
            default => AttendanceRecord::STATUS_LEAVE,
        };
    }

    private function generateLeaveDetails(): array
    {
        $reasons = [
            AttendanceRecord::LEAVE_REASON_FIELDWORK => 'Dinas diluar',
            AttendanceRecord::LEAVE_REASON_SICK => 'Sakit',
            AttendanceRecord::LEAVE_REASON_ABSENT => 'Alpa',
        ];

        $key = array_rand($reasons);

        return [$key, $reasons[$key]];
    }
}
