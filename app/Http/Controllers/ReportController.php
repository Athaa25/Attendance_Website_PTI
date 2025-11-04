<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale(config('app.locale'));

        $employees = Employee::with('department')->orderBy('full_name')->get();

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

        $selectedEmployeeInput = $request->query('employee_id', 'all');
        $selectedEmployeeId = $selectedEmployeeInput === 'all' || $selectedEmployeeInput === null || $selectedEmployeeInput === ''
            ? 'all'
            : (int) $selectedEmployeeInput;
        $selectedEmployee = $selectedEmployeeId === 'all'
            ? null
            : $employees->firstWhere('id', $selectedEmployeeId);

        $recordsQuery = AttendanceRecord::with('employee.department')
            ->whereBetween('attendance_date', [$start->toDateString(), $end->toDateString()]);

        if ($selectedEmployee) {
            $recordsQuery->where('employee_id', $selectedEmployee->id);
        }

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

        $matrixEmployees = $selectedEmployee ? collect([$selectedEmployee]) : $employees;
        $summaryMatrix = $matrixEmployees->map(function ($employee) use ($recordsByEmployee, $dateRange) {
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
        })->filter(function ($row) use ($selectedEmployeeId) {
            if ($selectedEmployeeId !== 'all') {
                return true;
            }

            foreach ($row['days'] as $value) {
                if ($value !== '') {
                    return true;
                }
            }

            return false;
        })->values();

        return view('sheet-report', [
            'employees' => $employees,
            'records' => $records,
            'summaryMatrix' => $summaryMatrix,
            'dateRange' => $dateRange,
            'viewMode' => $viewMode,
            'selectedEmployeeId' => $selectedEmployeeId,
            'selectedEmployee' => $selectedEmployee,
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
