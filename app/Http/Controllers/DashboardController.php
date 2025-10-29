<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale(config('app.locale'));
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $monthlyRecords = AttendanceRecord::with('employee')
            ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
            ->get();

        $presentCount = $monthlyRecords->where('status', AttendanceRecord::STATUS_PRESENT)->count();
        $lateCount = $monthlyRecords->where('status', AttendanceRecord::STATUS_LATE)->count();
        $leaveCount = $monthlyRecords->where('status', AttendanceRecord::STATUS_LEAVE)->count();
        $sickCount = $monthlyRecords->where('status', AttendanceRecord::STATUS_SICK)->count();
        $absentCount = $monthlyRecords->where('status', AttendanceRecord::STATUS_ABSENT)->count();
        $totalRecords = $monthlyRecords->count();

        $effectiveAttendance = $presentCount + $lateCount;
        $attendanceRate = $totalRecords > 0 ? round(($effectiveAttendance / $totalRecords) * 100, 1) : 0;

        $employeeCount = Employee::count();

        $recentAttendances = AttendanceRecord::with('employee')
            ->orderByDesc('attendance_date')
            ->orderBy('employee_id')
            ->limit(5)
            ->get();

        $chartRecords = AttendanceRecord::where('attendance_date', '>=', $now->copy()->subMonths(4)->startOfMonth())
            ->get()
            ->groupBy(fn ($record) => Carbon::parse($record->attendance_date)->format('Y-m'));

        $chartPeriod = CarbonPeriod::create($now->copy()->subMonths(4)->startOfMonth(), '1 month', $now->copy()->startOfMonth());
        $chartData = collect();

        foreach ($chartPeriod as $month) {
            $key = $month->format('Y-m');
            $count = $chartRecords->has($key) ? $chartRecords[$key]->count() : 0;

            $chartData->push([
                'label' => $month->translatedFormat('M'),
                'value' => $count,
            ]);
        }

        return view('dashboard', [
            'metrics' => [
                'total_absence' => $totalRecords,
                'late_count' => $lateCount,
                'attendance_rate' => $attendanceRate,
                'employee_count' => $employeeCount,
                'leave_count' => $leaveCount,
                'sick_count' => $sickCount,
                'absent_count' => $absentCount,
            ],
            'recentAttendances' => $recentAttendances,
            'monthlyChart' => $chartData,
            'now' => $now,
            'statusLabels' => AttendanceRecord::statusLabels(),
        ]);
    }
}
