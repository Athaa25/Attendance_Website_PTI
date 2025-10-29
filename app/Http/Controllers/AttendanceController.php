<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\AttendanceRecord;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale(config('app.locale'));
        $date = $request->date('date', now()->toDateString());
        $status = $request->string('status')->toString();
        $search = $request->string('search')->toString();

        $query = AttendanceRecord::with(['employee.department', 'employee.schedule', 'employee.user'])
            ->whereDate('attendance_date', $date);

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->whereHas('employee', function ($builder) use ($search) {
                $builder->where('full_name', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $records = $query->get()->sortBy(fn ($record) => $record->employee->full_name);

        $summary = [
            'present' => $records->where('status', AttendanceRecord::STATUS_PRESENT)->count(),
            'late' => $records->where('status', AttendanceRecord::STATUS_LATE)->count(),
            'leave' => $records->where('status', AttendanceRecord::STATUS_LEAVE)->count(),
            'sick' => $records->where('status', AttendanceRecord::STATUS_SICK)->count(),
            'absent' => $records->where('status', AttendanceRecord::STATUS_ABSENT)->count(),
            'total_employees' => Employee::count(),
        ];

        return view('dailly-attendance', [
            'page' => 'list',
            'attendanceDate' => Carbon::parse($date),
            'records' => $records,
            'summary' => $summary,
            'filters' => [
                'status' => $status,
                'search' => $search,
            ],
            'statusOptions' => AttendanceRecord::statusLabels(),
        ]);
    }

    public function edit(AttendanceRecord $attendanceRecord)
    {
        Carbon::setLocale(config('app.locale'));
        $attendanceRecord->load(['employee.department', 'employee.schedule', 'employee.user']);

        return view('dailly-attendance', [
            'page' => 'edit',
            'record' => $attendanceRecord,
            'records' => collect(),
            'attendanceDate' => $attendanceRecord->attendance_date,
            'statusOptions' => AttendanceRecord::statusLabels(),
        ]);
    }

    public function update(UpdateAttendanceRequest $request, AttendanceRecord $attendanceRecord)
    {
        $data = $request->validated();
        $employeeSchedule = $attendanceRecord->employee->schedule;

        if (in_array($data['status'], [AttendanceRecord::STATUS_PRESENT, AttendanceRecord::STATUS_LATE], true)) {
            $scheduleStart = $employeeSchedule?->start_time
                ? Carbon::parse($employeeSchedule->start_time)
                : Carbon::createFromTime(8, 0);

            $checkIn = $data['check_in_time']
                ? Carbon::createFromFormat('H:i', $data['check_in_time'])
                : $scheduleStart;

            $attendanceRecord->check_in_time = $checkIn->format('H:i');
            $attendanceRecord->check_out_time = $data['check_out_time'] ?? $checkIn->copy()->addHours(8)->format('H:i');

            $lateMinutes = max(0, $checkIn->diffInMinutes($scheduleStart));
            $attendanceRecord->late_minutes = $lateMinutes;
            $attendanceRecord->status = $lateMinutes > 5
                ? AttendanceRecord::STATUS_LATE
                : $data['status'];
        } else {
            $attendanceRecord->check_in_time = null;
            $attendanceRecord->check_out_time = null;
            $attendanceRecord->late_minutes = 0;
            $attendanceRecord->status = $data['status'];
        }

        $attendanceRecord->notes = $data['notes'];
        $attendanceRecord->save();

        return redirect()
            ->route('attendance.index', ['date' => $attendanceRecord->attendance_date->format('Y-m-d')])
            ->with('status', 'Data absensi berhasil diperbarui.');
    }
}
