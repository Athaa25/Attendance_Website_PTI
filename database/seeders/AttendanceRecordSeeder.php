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

                if (in_array($status, [AttendanceRecord::STATUS_PRESENT, AttendanceRecord::STATUS_LATE], true)) {
                    $scheduleStart = $employee->schedule?->start_time
                        ? Carbon::parse($employee->schedule->start_time)
                        : Carbon::createFromTime(8, 0);

                    $lateness = $status === AttendanceRecord::STATUS_LATE
                        ? random_int(5, 90)
                        : random_int(0, 5);

                    $actualStart = (clone $scheduleStart)->addMinutes($lateness);
                    $checkIn = $actualStart->format('H:i');
                    $checkOut = $actualStart->copy()->addHours(8)->format('H:i');
                    $lateMinutes = max(0, $actualStart->diffInMinutes($scheduleStart));
                    $status = $lateMinutes > 5 ? AttendanceRecord::STATUS_LATE : AttendanceRecord::STATUS_PRESENT;
                    $notes = $lateMinutes > 5 ? 'Datang terlambat' : 'Tepat waktu';
                } elseif ($status === AttendanceRecord::STATUS_LEAVE) {
                    $notes = 'Izin tidak masuk';
                } elseif ($status === AttendanceRecord::STATUS_SICK) {
                    $notes = 'Sakit dengan surat dokter';
                } else {
                    $notes = 'Tanpa keterangan';
                }

                AttendanceRecord::create([
                    'employee_id' => $employee->id,
                    'attendance_date' => $date,
                    'status' => $status,
                    'check_in_time' => $checkIn,
                    'check_out_time' => $checkOut,
                    'late_minutes' => $lateMinutes,
                    'notes' => $notes,
                ]);
            }
        }
    }

    private function determineStatus(): string
    {
        $roll = random_int(1, 100);

        return match (true) {
            $roll <= 70 => AttendanceRecord::STATUS_PRESENT,
            $roll <= 85 => AttendanceRecord::STATUS_LATE,
            $roll <= 93 => AttendanceRecord::STATUS_LEAVE,
            $roll <= 97 => AttendanceRecord::STATUS_SICK,
            default => AttendanceRecord::STATUS_ABSENT,
        };
    }
}
