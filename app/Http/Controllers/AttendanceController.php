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

        $dateInput = $request->input('date');
        $date = $dateInput && Carbon::hasFormat($dateInput, 'Y-m-d')
            ? Carbon::createFromFormat('Y-m-d', $dateInput)->toDateString()
            : now()->toDateString();

        $statusOptions = AttendanceRecord::statusLabels();
        $status = $request->string('status')->toString();
        if ($status !== '' && ! array_key_exists($status, $statusOptions)) {
            $status = '';
        }
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

        $statusSummaryMap = [
            'present' => AttendanceRecord::STATUS_PRESENT,
            'late' => AttendanceRecord::STATUS_LATE,
            'leave' => AttendanceRecord::STATUS_LEAVE,
            'sick' => AttendanceRecord::STATUS_SICK,
            'absent' => AttendanceRecord::STATUS_ABSENT,
        ];

        $statusCounts = $records
            ->groupBy('status')
            ->map->count();

        $summary = ['total_employees' => Employee::count()];

        foreach ($statusSummaryMap as $summaryKey => $statusValue) {
            $summary[$summaryKey] = $statusCounts->get($statusValue, 0);
        }

        return view('attendance', [
            'page' => 'list',
            'viewMode' => 'list',
            'attendanceDate' => Carbon::createFromFormat('Y-m-d', $date),
            'records' => $records,
            'summary' => $summary,
            'filters' => [
                'status' => $status,
                'search' => $search,
            ],
            'statusOptions' => $statusOptions,
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

        return view('attendance', array_merge(
            $this->formPayload($attendanceRecord),
            [
                'page' => 'form',
                'attendanceDate' => $attendanceRecord->attendance_date ?? Carbon::now(),
            ]
        ));
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
            'statusOptions' => AttendanceRecord::statusLabels(),
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
