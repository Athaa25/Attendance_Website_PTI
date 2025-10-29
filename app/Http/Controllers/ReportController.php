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
        $allowedTypes = ['harian', 'mingguan', 'bulanan', 'custom'];
        $type = $request->query('type', 'harian');
        $activeType = in_array($type, $allowedTypes, true) ? $type : 'harian';

        $employees = Employee::with('department')->orderBy('full_name')->get();

        $dailyRecords = collect();
        $weeklySummary = collect();
        $monthlyMatrix = collect();
        $customRecords = collect();
        $selectedEmployee = null;
        $period = [];

        switch ($activeType) {
            case 'mingguan':
                $start = $this->parseDate($request->query('start'), now()->startOfWeek());
                $end = $this->parseDate($request->query('end'), $start->copy()->endOfWeek());
                $period = ['start' => $start, 'end' => $end];

                $weeklyRecords = AttendanceRecord::with('employee.department')
                    ->whereBetween('attendance_date', [$start->toDateString(), $end->toDateString()])
                    ->get();

                $weeklySummary = $weeklyRecords->groupBy('employee_id')->map(function ($items) {
                    $employee = $items->first()->employee;
                    $attended = $items->whereIn('status', [AttendanceRecord::STATUS_PRESENT, AttendanceRecord::STATUS_LATE])->count();
                    $leave = $items->whereIn('status', [AttendanceRecord::STATUS_LEAVE, AttendanceRecord::STATUS_SICK])->count();
                    $absent = $items->where('status', AttendanceRecord::STATUS_ABSENT)->count();

                    return [
                        'employee' => $employee,
                        'total' => $items->count(),
                        'attended' => $attended,
                        'leave' => $leave,
                        'absent' => $absent,
                    ];
                });
                break;

            case 'bulanan':
                $month = (int) $request->query('month', now()->month);
                $year = (int) $request->query('year', now()->year);
                $periodStart = Carbon::create($year, $month, 1)->startOfMonth();
                $periodEnd = $periodStart->copy()->endOfMonth();
                $period = ['month' => $month, 'year' => $year, 'start' => $periodStart, 'end' => $periodEnd];

                $monthlyRecords = AttendanceRecord::with('employee.department')
                    ->whereBetween('attendance_date', [$periodStart->toDateString(), $periodEnd->toDateString()])
                    ->get()
                    ->groupBy('employee_id');

                $monthlyMatrix = $employees->map(function ($employee) use ($monthlyRecords, $periodStart, $periodEnd) {
                    $days = [];
                    $records = $monthlyRecords->get($employee->id, collect())->keyBy(fn ($item) => Carbon::parse($item->attendance_date)->format('Y-m-d'));

                    for ($date = $periodStart->copy(); $date->lte($periodEnd); $date->addDay()) {
                        $key = $date->format('Y-m-d');
                        $record = $records->get($key);
                        $days[$date->day] = $record ? $this->statusSymbol($record->status) : '';
                    }

                    return [
                        'employee' => $employee,
                        'days' => $days,
                    ];
                });
                break;

            case 'custom':
                $selectedEmployeeId = (int) $request->query('employee_id');
                $selectedEmployee = $employees->firstWhere('id', $selectedEmployeeId) ?? $employees->first();
                $start = $this->parseDate($request->query('start'), now()->startOfMonth());
                $end = $this->parseDate($request->query('end'), now());
                $period = ['start' => $start, 'end' => $end];

                if ($selectedEmployee) {
                    $customRecords = AttendanceRecord::with('employee.department')
                        ->where('employee_id', $selectedEmployee->id)
                        ->whereBetween('attendance_date', [$start->toDateString(), $end->toDateString()])
                        ->orderBy('attendance_date')
                        ->get();
                }
                break;

            case 'harian':
            default:
                $date = $this->parseDate($request->query('date'), now());
                $period = ['date' => $date];

                $dailyRecords = AttendanceRecord::with('employee.department', 'employee.schedule')
                    ->whereDate('attendance_date', $date)
                    ->orderBy('employee_id')
                    ->get();
                break;
        }

        return view('sheet-report', [
            'activeType' => $activeType,
            'employees' => $employees,
            'dailyRecords' => $dailyRecords,
            'weeklySummary' => $weeklySummary,
            'monthlyMatrix' => $monthlyMatrix,
            'customRecords' => $customRecords,
            'selectedEmployee' => $selectedEmployee,
            'period' => $period,
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
