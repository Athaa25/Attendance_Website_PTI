<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\AttendanceRecord;
use App\Models\AttendanceReason;
use App\Models\AttendanceStatus;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class AttendanceSyncService
{
    public function syncAttendanceRecordFromAbsensi(Absensi $absensi, ?Employee $employee = null): ?AttendanceRecord
    {
        if (! Schema::hasTable('attendance_records')) {
            return null;
        }

        $day = $this->resolveAbsensiDay($absensi);
        if (! $day) {
            return null;
        }

        if (! $employee) {
            $employee = $this->resolveEmployeeFromAbsensi($absensi);
        }
        if (! $employee) {
            return null;
        }

        $employee->loadMissing('schedule');

        $payload = $this->mapAbsensiToAttendance($absensi);

        $record = AttendanceRecord::query()->firstOrNew([
            'employee_id' => $employee->id,
            'attendance_date' => $day,
        ]);

        $record->status = $payload['status'];
        $record->leave_reason = $payload['leave_reason'];
        $record->notes = $payload['notes'];
        $record->check_in_time = $payload['check_in_time'];
        $record->check_out_time = $payload['check_out_time'];
        $record->late_minutes = $this->calculateLateMinutes($employee, $payload['check_in_time'], $payload['status']);
        $record->status_id = $this->resolveStatusId($payload['status']);
        $record->reason_id = $this->resolveReasonId($payload['leave_reason']);

        $record->save();

        return $record;
    }

    public function syncAbsensiFromAttendanceRecord(AttendanceRecord $record): ?Absensi
    {
        if (! Schema::hasTable('absensis')) {
            return null;
        }

        $record->loadMissing('employee');
        $employee = $record->employee;

        if (! $employee) {
            return null;
        }

        $day = $record->attendance_date?->format('Y-m-d');
        if (! $day) {
            return null;
        }

        $row = Absensi::query()
            ->where('name', $employee->full_name)
            ->where('day', $day)
            ->first();

        if (! $row) {
            $row = new Absensi();
            $row->name = $employee->full_name;
            $row->day = $day;
            $row->time = now();
        }

        $payload = $this->mapAttendanceToAbsensi($record);
        $meta = $payload['meta'] ?? [];
        if (! is_array($meta)) {
            $meta = [];
        }
        $meta['employee_id'] = $employee->id;
        $payload['meta'] = $meta;

        $row->check_in_time = $payload['check_in_time'];
        $row->check_out_time = $payload['check_out_time'];
        $row->check_in_status = $payload['check_in_status'];
        $row->check_out_status = $payload['check_out_status'];
        $row->meta = $payload['meta'] !== null ? json_encode($payload['meta']) : null;

        if (! $row->day) {
            $row->day = $day;
        }
        if (! $row->time) {
            $row->time = now();
        }

        $row->save();

        return $row;
    }

    public function resolveEmployeeFromAbsensi(Absensi $absensi): ?Employee
    {
        $meta = $this->normalizeMeta($absensi->meta ?? null);
        $employeeId = $meta['employee_id'] ?? null;

        if ($employeeId) {
            $employee = Employee::query()->find((int) $employeeId);
            if ($employee) {
                return $employee;
            }
        }

        $name = trim((string) $absensi->name);
        if ($name === '') {
            return null;
        }

        return $this->resolveEmployeeByName($name);
    }

    private function resolveAbsensiDay(Absensi $absensi): ?string
    {
        if ($absensi->day) {
            return (string) $absensi->day;
        }

        if ($absensi->time) {
            try {
                return Carbon::parse($absensi->time)->toDateString();
            } catch (\Throwable $e) {
                return null;
            }
        }

        return null;
    }

    private function mapAbsensiToAttendance(Absensi $absensi): array
    {
        $meta = $this->normalizeMeta($absensi->meta ?? null);
        $reasonName = trim((string) ($meta['reason_name'] ?? ''));

        if ($reasonName !== '') {
            [$status, $reason, $notes] = $this->mapReasonNameToStatus($reasonName);

            return [
                'status' => $status,
                'leave_reason' => $reason,
                'notes' => $notes,
                'check_in_time' => null,
                'check_out_time' => null,
            ];
        }

        $checkInTime = $this->normalizeTime($absensi->check_in_time);
        $checkOutTime = $this->normalizeTime($absensi->check_out_time);

        $status = AttendanceRecord::STATUS_PRESENT;
        if ($absensi->check_in_status === 'Late') {
            $status = AttendanceRecord::STATUS_LATE;
        } elseif ($absensi->check_in_status === null && $checkInTime !== null) {
            $status = $this->isLateCheckIn($checkInTime)
                ? AttendanceRecord::STATUS_LATE
                : AttendanceRecord::STATUS_PRESENT;
        }

        return [
            'status' => $status,
            'leave_reason' => null,
            'notes' => null,
            'check_in_time' => $checkInTime,
            'check_out_time' => $checkOutTime,
        ];
    }

    private function mapAttendanceToAbsensi(AttendanceRecord $record): array
    {
        $status = $record->statusDefinition?->code ?? $record->status;
        $checkInTime = $this->normalizeTime($record->check_in_time);
        $checkOutTime = $this->normalizeTime($record->check_out_time);

        if (in_array($status, [
            AttendanceRecord::STATUS_LEAVE,
            AttendanceRecord::STATUS_SICK,
            AttendanceRecord::STATUS_ABSENT,
        ], true)) {
            return [
                'check_in_time' => null,
                'check_out_time' => null,
                'check_in_status' => null,
                'check_out_status' => null,
                'meta' => [
                    'reason_name' => $this->mapStatusToReasonName($status),
                ],
            ];
        }

        $checkInStatus = null;
        if ($checkInTime !== null) {
            $checkInStatus = $status === AttendanceRecord::STATUS_LATE ? 'Late' : 'On Time';
        }

        $checkOutStatus = null;
        if ($checkOutTime !== null) {
            $checkOutStatus = $this->deriveCheckOutStatus($checkOutTime);
        }

        return [
            'check_in_time' => $checkInTime,
            'check_out_time' => $checkOutTime,
            'check_in_status' => $checkInStatus,
            'check_out_status' => $checkOutStatus,
            'meta' => null,
        ];
    }

    private function mapReasonNameToStatus(string $reasonName): array
    {
        $lower = mb_strtolower(trim($reasonName));
        if ($lower === 'sakit') {
            return [AttendanceRecord::STATUS_SICK, AttendanceRecord::LEAVE_REASON_SICK, null];
        }

        if ($lower === 'izin') {
            return [AttendanceRecord::STATUS_LEAVE, 'other', 'Izin'];
        }

        if ($lower === 'tanpa keterangan') {
            return [AttendanceRecord::STATUS_ABSENT, AttendanceRecord::LEAVE_REASON_ABSENT, null];
        }

        return [AttendanceRecord::STATUS_ABSENT, AttendanceRecord::LEAVE_REASON_ABSENT, $reasonName];
    }

    private function mapStatusToReasonName(string $status): string
    {
        switch ($status) {
            case AttendanceRecord::STATUS_SICK:
                return 'Sakit';
            case AttendanceRecord::STATUS_LEAVE:
                return 'Izin';
            case AttendanceRecord::STATUS_ABSENT:
            default:
                return 'Tanpa Keterangan';
        }
    }

    private function normalizeMeta($meta): array
    {
        if (is_string($meta)) {
            $decoded = json_decode($meta, true);
            return is_array($decoded) ? $decoded : [];
        }
        if (is_array($meta)) {
            return $meta;
        }
        if (is_object($meta)) {
            return (array) $meta;
        }

        return [];
    }

    private function normalizeTime($value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value->format('H:i:s');
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('H:i:s');
        }

        $time = trim((string) $value);
        if ($time === '') {
            return null;
        }

        return $time;
    }

    private function calculateLateMinutes(Employee $employee, ?string $checkInTime, string $status): int
    {
        if ($checkInTime === null || $status !== AttendanceRecord::STATUS_LATE) {
            return 0;
        }

        $scheduleStart = $employee->schedule?->start_time
            ? Carbon::parse($employee->schedule->start_time)
            : Carbon::createFromTime(8, 0);

        $format = strlen($checkInTime) === 5 ? 'H:i' : 'H:i:s';
        $actualStart = Carbon::createFromFormat($format, $checkInTime);

        return max(0, $actualStart->diffInMinutes($scheduleStart));
    }

    private function resolveStatusId(?string $status): ?int
    {
        if (! $status || ! Schema::hasTable('attendance_statuses')) {
            return null;
        }

        return AttendanceStatus::query()->where('code', $status)->value('id');
    }

    private function resolveReasonId(?string $reason): ?int
    {
        if (! $reason || ! Schema::hasTable('attendance_reasons')) {
            return null;
        }

        return AttendanceReason::query()->where('code', $reason)->value('id');
    }

    private function resolveEmployeeByName(string $name): ?Employee
    {
        $name = trim($name);
        if ($name === '') {
            return null;
        }

        $lower = mb_strtolower($name);
        $normalized = str_replace('_', ' ', $lower);

        $employee = Employee::query()
            ->whereRaw('LOWER(full_name) = ?', [$lower])
            ->orWhereRaw('LOWER(full_name) = ?', [$normalized])
            ->first();

        if ($employee) {
            return $employee;
        }

        $slug = $this->slugName($name);
        $candidates = Employee::query()->get(['id', 'full_name']);
        foreach ($candidates as $candidate) {
            if ($this->slugName($candidate->full_name) === $slug) {
                return $candidate;
            }
        }

        return null;
    }

    private function slugName(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9\\-_. ]+/', '', $slug);
        $slug = preg_replace('/\\s+/', '_', $slug);

        return $slug !== '' ? $slug : 'user_' . time();
    }

    private function isLateCheckIn(string $checkInTime): bool
    {
        $workStart = config('attendance.work_start', env('WORK_START', '10:00'));
        $grace = (int) config('attendance.grace_minutes', env('GRACE_MINUTES', 0));

        $format = strlen($checkInTime) === 5 ? 'H:i' : 'H:i:s';

        try {
            $checkIn = Carbon::createFromFormat($format, $checkInTime);
        } catch (\Throwable $e) {
            return false;
        }

        $startCut = Carbon::now()->copy()->setTimeFromTimeString($workStart)->addMinutes($grace);

        return $checkIn->greaterThan($startCut);
    }

    private function deriveCheckOutStatus(string $checkOutTime): string
    {
        $workEnd = config('attendance.work_end', env('WORK_END', '16:00'));
        $grace = (int) config('attendance.grace_minutes', env('GRACE_MINUTES', 0));

        $format = strlen($checkOutTime) === 5 ? 'H:i' : 'H:i:s';
        $time = Carbon::createFromFormat($format, $checkOutTime);

        $endCut = Carbon::now()->copy()->setTimeFromTimeString($workEnd)->subMinutes($grace);

        return $time->greaterThanOrEqualTo($endCut) ? 'On Time' : 'Early';
    }
}
