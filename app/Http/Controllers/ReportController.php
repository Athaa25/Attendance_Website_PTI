<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale(config('app.locale'));

        $selectedDepartmentId = $request->integer('department_id');
        $selectedPositionId = $request->integer('position_id');
        $searchName = trim((string) $request->query('name', ''));

        $employees = Employee::with(['department', 'position'])
            ->when($selectedDepartmentId, fn ($q) => $q->where('department_id', $selectedDepartmentId))
            ->when($selectedPositionId, fn ($q) => $q->where('position_id', $selectedPositionId))
            ->when($searchName !== '', fn ($q) => $q->where('full_name', 'like', '%' . $searchName . '%'))
            ->orderBy('full_name')
            ->get();

        $allowedViewModes = ['detail', 'summary'];
        $requestedView = $request->query('view');
        $viewMode = in_array($requestedView, $allowedViewModes, true) ? $requestedView : null;

        $type = $request->query('type');
        $defaultStart = now()->startOfMonth();
        $defaultEnd = now();

        if ($type === 'harian') {
            $defaultDate = $this->parseDate($request->query('date'), now());
            $defaultStart = $defaultDate->copy();
            $defaultEnd = $defaultDate->copy();
        } elseif ($type === 'mingguan') {
            $defaultStart = $this->parseDate($request->query('start'), now()->startOfWeek());
            $defaultEnd = $defaultStart->copy()->endOfWeek();
        } elseif ($type === 'bulanan') {
            $month = (int) $request->query('month', now()->month);
            $year = (int) $request->query('year', now()->year);
            $defaultStart = Carbon::create($year, $month, 1)->startOfMonth();
            $defaultEnd = $defaultStart->copy()->endOfMonth();

            if (! $viewMode) {
                $viewMode = 'summary';
            }
        }

        $start = $this->parseDate($request->query('start'), $defaultStart);
        $end = $this->parseDate($request->query('end'), $defaultEnd);

        if ($start->gt($end)) {
            [$start, $end] = [$end->copy(), $start->copy()];
        }

        if (! $viewMode) {
            $viewMode = 'detail';
        }

        $recordsQuery = AttendanceRecord::with('employee.department')
            ->whereBetween('attendance_date', [$start->toDateString(), $end->toDateString()]);

        $recordsQuery->when($selectedDepartmentId, function ($q) use ($selectedDepartmentId) {
            $q->whereHas('employee', fn ($builder) => $builder->where('department_id', $selectedDepartmentId));
        });

        $recordsQuery->when($selectedPositionId, function ($q) use ($selectedPositionId) {
            $q->whereHas('employee', fn ($builder) => $builder->where('position_id', $selectedPositionId));
        });

        $recordsQuery->when($searchName !== '', function ($q) use ($searchName) {
            $q->whereHas('employee', fn ($builder) => $builder->where('full_name', 'like', '%' . $searchName . '%'));
        });

        $records = $recordsQuery
            ->orderBy('attendance_date')
            ->orderBy('employee_id')
            ->get()
            ->sortBy(fn ($record) => sprintf('%s-%s', $record->attendance_date->format('Ymd'), strtolower($record->employee->full_name)))
            ->values();

        $dateRange = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dateRange[] = $date->copy();
        }

        $recordsByEmployee = $records->groupBy('employee_id')->map(function ($items) {
            return $items->keyBy(fn ($item) => $item->attendance_date->format('Y-m-d'));
        });

        $summaryMatrix = $employees->map(function ($employee) use ($recordsByEmployee, $dateRange) {
            $employeeRecords = $recordsByEmployee->get($employee->id, collect());
            $days = [];

            foreach ($dateRange as $date) {
                $key = $date->format('Y-m-d');
                $record = $employeeRecords->get($key);
                $days[$key] = $record ? $this->statusSymbol($record->status) : '';
            }

            return [
                'employee' => $employee,
                'days' => $days,
            ];
        })->filter(function ($row) {
            foreach ($row['days'] as $value) {
                if ($value !== '') {
                    return true;
                }
            }

            return false;
        })->values();

        return view('reports.sheet', [
            'employees' => $employees,
            'departments' => Department::orderBy('name')->get(),
            'positions' => Position::orderBy('name')->get(),
            'records' => $records,
            'summaryMatrix' => $summaryMatrix,
            'dateRange' => $dateRange,
            'viewMode' => $viewMode,
            'searchName' => $searchName,
            'selectedDepartmentId' => $selectedDepartmentId,
            'selectedPositionId' => $selectedPositionId,
            'startDate' => $start,
            'endDate' => $end,
            'statusLabels' => AttendanceRecord::statusLabels(),
        ]);
    }

    private function parseDate(?string $value, Carbon $default): Carbon
    {
        if (! $value) {
            return $default->copy();
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return $default->copy();
        }
    }

    private function statusSymbol(string $status): string
    {
        return match ($status) {
            AttendanceRecord::STATUS_PRESENT => 'H',
            AttendanceRecord::STATUS_LATE => 'T',
            AttendanceRecord::STATUS_LEAVE => 'I',
            AttendanceRecord::STATUS_SICK => 'S',
            AttendanceRecord::STATUS_ABSENT => 'A',
            default => '',
        };
    }
}
