<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\AttendanceReason;
use App\Models\AttendanceRecord;
use App\Models\AttendanceStatus;
use App\Models\Employee;
use App\Models\PresenceTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale(config('app.locale'));

        $dateInput = $request->input('date');
        $date = $dateInput && Carbon::hasFormat($dateInput, 'Y-m-d')
            ? Carbon::createFromFormat('Y-m-d', $dateInput)->toDateString()
            : now()->toDateString();

        $statusCollection = $this->statusOptionsCollection();
        $statusOptions = $statusCollection->pluck('label', 'code')->toArray();
        $status = $request->string('status')->toString();
        $selectedStatus = $statusCollection->firstWhere('code', $status);
        if ($status !== '' && ! $selectedStatus) {
            $status = '';
        }
        $search = $request->string('search')->toString();

        $query = AttendanceRecord::with([
            'employee.department',
            'employee.schedule',
            'employee.user',
            'statusDefinition',
            'reasonDefinition',
            'checkInTimeSlot',
            'checkOutTimeSlot',
        ])
            ->whereDate('attendance_date', $date);

        if ($selectedStatus) {
            $query->where('status_id', $selectedStatus->id);
        }

        if ($search !== '') {
            $query->whereHas('employee', function ($builder) use ($search) {
                $builder->where('full_name', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $records = $query->get()->sortBy(fn ($record) => $record->employee->full_name);

        $statusCounts = $records
            ->groupBy(fn ($record) => $record->statusDefinition?->code ?? $record->status)
            ->map->count();

        $summary = ['total_employees' => Employee::count()];
        foreach ([
            AttendanceRecord::STATUS_PRESENT,
            AttendanceRecord::STATUS_LATE,
            AttendanceRecord::STATUS_LEAVE,
            AttendanceRecord::STATUS_SICK,
            AttendanceRecord::STATUS_ABSENT,
        ] as $statusCode) {
            $summary[$statusCode] = $statusCounts->get($statusCode, 0);
        }

        return view('attendance.index', [
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
            'leaveReasonOptions' => $this->reasonOptionsCollection()->pluck('label', 'code')->toArray(),
        ]);
    }

    public function create()
    {
        return view('attendance.index', $this->formPayload());
    }

    public function store(StoreAttendanceRequest $request)
    {
        $data = $request->validated();
        $employee = Employee::with('schedule')->findOrFail($data['employee_id']);

        $attendanceRecord = DB::transaction(function () use ($data, $employee, $request) {
            $record = new AttendanceRecord();
            return $this->fillAttendanceRecord($record, $data, $employee, $request->file('supporting_document'));
        });

        return redirect()
            ->route('attendance.index', ['date' => $attendanceRecord->attendance_date->format('Y-m-d')])
            ->with('status', 'Absensi berhasil ditambahkan.');
    }

    public function edit(AttendanceRecord $attendanceRecord)
    {
        $attendanceRecord->load(['employee.department', 'employee.schedule', 'employee.user']);

        return view('attendance.index', array_merge(
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

        DB::transaction(function () use ($attendanceRecord, $data, $employee, $request) {
            $this->fillAttendanceRecord(
                $attendanceRecord,
                $data,
                $employee,
                $request->file('supporting_document')
            );
        });

        return redirect()
            ->route('attendance.index', ['date' => $attendanceRecord->attendance_date->format('Y-m-d')])
            ->with('status', 'Data absensi berhasil diperbarui.');
    }

    private function formPayload(?AttendanceRecord $record = null): array
    {
        $employees = Employee::with('schedule')->orderBy('full_name')->get();

        return [
            'viewMode' => $record ? 'edit' : 'create',
            'record' => $record?->loadMissing([
                'employee.department',
                'employee.schedule',
                'statusDefinition',
                'reasonDefinition',
                'checkInTimeSlot',
                'checkOutTimeSlot',
            ]),
            'employees' => $employees,
            'leaveReasonOptions' => $this->reasonOptionsCollection()->pluck('label', 'code')->toArray(),
            'statusOptions' => $this->statusOptionsCollection()->pluck('label', 'code')->toArray(),
        ];
    }

    private function fillAttendanceRecord(
        AttendanceRecord $attendanceRecord,
        array $data,
        Employee $employee,
        ?UploadedFile $file
    ): AttendanceRecord {
        $attendanceRecord->loadMissing(['checkInTimeSlot', 'checkOutTimeSlot']);

        $statusDefinition = AttendanceStatus::query()->where('code', $data['status'])->first();
        if (! $statusDefinition) {
            throw ValidationException::withMessages(['status' => 'Status absensi tidak ditemukan.']);
        }

        $reasonDefinition = null;
        if ($statusDefinition->requires_reason) {
            $reasonDefinition = AttendanceReason::query()->where('code', $data['leave_reason'])->first();
            if (! $reasonDefinition) {
                throw ValidationException::withMessages(['leave_reason' => 'Alasan absensi tidak valid.']);
            }
        }

        $attendanceRecord->employee_id = $employee->id;
        $attendanceRecord->attendance_date = $data['attendance_date'];
        $attendanceRecord->status_id = $statusDefinition->id;
        $attendanceRecord->status = $statusDefinition->code;
        $attendanceRecord->reason_id = $reasonDefinition?->id;
        $attendanceRecord->leave_reason = $reasonDefinition?->code;
        $attendanceRecord->notes = $data['notes'] ?? null;

        $checkInTime = $statusDefinition->code === AttendanceRecord::STATUS_PRESENT
            ? ($data['check_in_time'] ?? null)
            : null;
        $checkOutTime = $statusDefinition->code === AttendanceRecord::STATUS_PRESENT
            ? ($data['check_out_time'] ?? null)
            : null;

        $attendanceRecord->check_in_time = $checkInTime;
        $attendanceRecord->check_out_time = $checkOutTime;
        $attendanceRecord->late_minutes = $this->calculateLateMinutes($employee, $checkInTime);

        $attendanceRecord->check_in_time_id = $this->syncPresenceTime(
            $attendanceRecord->checkInTimeSlot,
            $employee,
            PresenceTime::TYPE_CHECK_IN,
            $checkInTime,
            $attendanceRecord->attendance_date
        );

        $attendanceRecord->check_out_time_id = $this->syncPresenceTime(
            $attendanceRecord->checkOutTimeSlot,
            $employee,
            PresenceTime::TYPE_CHECK_OUT,
            $checkOutTime,
            $attendanceRecord->attendance_date
        );

        $this->handleSupportingDocument($attendanceRecord, $file, (bool) $statusDefinition->requires_reason);

        $attendanceRecord->save();

        return $attendanceRecord;
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

    private function handleSupportingDocument(
        AttendanceRecord $attendanceRecord,
        ?UploadedFile $file,
        bool $requiresDocument
    ): void {
        if ($file) {
            if ($attendanceRecord->supporting_document_path) {
                Storage::disk('public')->delete($attendanceRecord->supporting_document_path);
            }

            $attendanceRecord->supporting_document_path = $file->store('attendance-supporting', 'public');

            return;
        }

        if (! $requiresDocument && $attendanceRecord->supporting_document_path) {
            Storage::disk('public')->delete($attendanceRecord->supporting_document_path);
            $attendanceRecord->supporting_document_path = null;
        }
    }

    private function syncPresenceTime(
        ?PresenceTime $presenceTime,
        Employee $employee,
        string $type,
        ?string $timeValue,
        ?Carbon $attendanceDate
    ): ?int {
        if (! $timeValue) {
            if ($presenceTime) {
                $presenceTime->delete();
            }

            return null;
        }

        $timestamp = $attendanceDate
            ? Carbon::createFromFormat('Y-m-d H:i', "{$attendanceDate->format('Y-m-d')} {$timeValue}")
            : null;

        $payload = [
            'employee_id' => $employee->id,
            'type' => $type,
            'recorded_at' => $timestamp,
            'checkin_time' => $type === PresenceTime::TYPE_CHECK_IN ? $timeValue : null,
            'checkout_time' => $type === PresenceTime::TYPE_CHECK_OUT ? $timeValue : null,
        ];

        if ($presenceTime) {
            $presenceTime->fill($payload)->save();

            return $presenceTime->id;
        }

        return PresenceTime::query()->create($payload)->id;
    }

    private function statusOptionsCollection(): Collection
    {
        return AttendanceStatus::query()->orderBy('id')->get();
    }

    private function reasonOptionsCollection(): Collection
    {
        return AttendanceReason::query()->orderBy('id')->get();
    }
}
