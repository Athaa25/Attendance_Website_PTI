<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\AttendanceRecord;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

        if (in_array($status, [AttendanceRecord::STATUS_PRESENT, AttendanceRecord::STATUS_LEAVE], true)) {
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
            'leave' => $records->where('status', AttendanceRecord::STATUS_LEAVE)->count(),
            'total_employees' => Employee::count(),
        ];

        return view('attendance', [
            'viewMode' => 'list',
            'attendanceDate' => Carbon::parse($date),
            'records' => $records,
            'summary' => $summary,
            'filters' => [
                'status' => $status,
                'search' => $search,
            ],
            'statusOptions' => [
                AttendanceRecord::STATUS_PRESENT => AttendanceRecord::statusLabels()[AttendanceRecord::STATUS_PRESENT],
                AttendanceRecord::STATUS_LEAVE => AttendanceRecord::statusLabels()[AttendanceRecord::STATUS_LEAVE],
            ],
            'leaveReasonOptions' => AttendanceRecord::leaveReasonOptions(),
        ]);
    }

    public function create()
    {
        return view('attendance', $this->formPayload());
    }

    public function store(StoreAttendanceRequest $request)
    {
        $data = $request->validated();
        $employee = Employee::with('schedule')->findOrFail($data['employee_id']);

        $attendanceRecord = new AttendanceRecord();
        $this->fillAttendanceRecord($attendanceRecord, $data, $employee, $request->file('supporting_document'));

        return redirect()
            ->route('attendance.index', ['date' => $attendanceRecord->attendance_date->format('Y-m-d')])
            ->with('status', 'Absensi berhasil ditambahkan.');
    }

    public function edit(AttendanceRecord $attendanceRecord)
    {
        $attendanceRecord->load(['employee.department', 'employee.schedule', 'employee.user']);

        return view('attendance', $this->formPayload($attendanceRecord));
    }

    public function update(UpdateAttendanceRequest $request, AttendanceRecord $attendanceRecord)
    {
        $data = $request->validated();
        $employee = Employee::with('schedule')->findOrFail($data['employee_id']);

        $this->fillAttendanceRecord(
            $attendanceRecord,
            $data,
            $employee,
            $request->file('supporting_document')
        );

        return redirect()
            ->route('attendance.index', ['date' => $attendanceRecord->attendance_date->format('Y-m-d')])
            ->with('status', 'Data absensi berhasil diperbarui.');
    }

    private function formPayload(?AttendanceRecord $record = null): array
    {
        $employees = Employee::with('schedule')->orderBy('full_name')->get();

        return [
            'viewMode' => $record ? 'edit' : 'create',
            'record' => $record?->loadMissing(['employee.department', 'employee.schedule']),
            'employees' => $employees,
            'leaveReasonOptions' => AttendanceRecord::leaveReasonOptions(),
            'statusOptions' => [
                AttendanceRecord::STATUS_PRESENT => AttendanceRecord::statusLabels()[AttendanceRecord::STATUS_PRESENT],
                AttendanceRecord::STATUS_LEAVE => AttendanceRecord::statusLabels()[AttendanceRecord::STATUS_LEAVE],
            ],
        ];
    }

    private function fillAttendanceRecord(
        AttendanceRecord $attendanceRecord,
        array $data,
        Employee $employee,
        ?UploadedFile $file
    ): void {
        $attendanceRecord->employee_id = $employee->id;
        $attendanceRecord->attendance_date = $data['attendance_date'];
        $attendanceRecord->status = $data['status'];
        $attendanceRecord->leave_reason = $data['status'] === AttendanceRecord::STATUS_LEAVE
            ? ($data['leave_reason'] ?? null)
            : null;
        $attendanceRecord->notes = $data['notes'] ?? null;

        if ($attendanceRecord->status === AttendanceRecord::STATUS_PRESENT) {
            $attendanceRecord->check_in_time = $data['check_in_time'] ?? null;
            $attendanceRecord->check_out_time = $data['check_out_time'] ?? null;
            $attendanceRecord->late_minutes = $this->calculateLateMinutes(
                $employee,
                $attendanceRecord->check_in_time
            );
        } else {
            $attendanceRecord->check_in_time = null;
            $attendanceRecord->check_out_time = null;
            $attendanceRecord->late_minutes = 0;
        }

        $this->handleSupportingDocument($attendanceRecord, $file);

        $attendanceRecord->save();
    }

    private function calculateLateMinutes(Employee $employee, ?string $checkInTime): int
    {
        if (! $checkInTime) {
            return 0;
        }

        $scheduleStart = $employee->schedule?->start_time
            ? Carbon::parse($employee->schedule->start_time)
            : Carbon::createFromTime(8, 0);

        $actualStart = Carbon::createFromFormat('H:i', $checkInTime);

        return max(0, $actualStart->diffInMinutes($scheduleStart));
    }

    private function handleSupportingDocument(AttendanceRecord $attendanceRecord, ?UploadedFile $file): void
    {
        if ($file) {
            if ($attendanceRecord->supporting_document_path) {
                Storage::disk('public')->delete($attendanceRecord->supporting_document_path);
            }

            $attendanceRecord->supporting_document_path = $file->store('attendance-supporting', 'public');

            return;
        }

        if ($attendanceRecord->status !== AttendanceRecord::STATUS_LEAVE && $attendanceRecord->supporting_document_path) {
            Storage::disk('public')->delete($attendanceRecord->supporting_document_path);
            $attendanceRecord->supporting_document_path = null;
        }
    }
}
