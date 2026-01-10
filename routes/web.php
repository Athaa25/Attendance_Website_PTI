<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FaceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/metrics', [DashboardController::class, 'metrics'])->name('dashboard.metrics');

    Route::prefix('manage-users')->name('manage-users.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/search', [EmployeeController::class, 'search'])->name('search');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->name('show');
        Route::delete('/{employee}/face-photos', [EmployeeController::class, 'deleteFacePhotos'])->name('face-photos.destroy');
        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
    });

    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::get('/departments/search', [DepartmentController::class, 'search'])->name('departments.search');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{position}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{position}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{position}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    Route::get('/daily-attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/daily-attendance/search', [AttendanceController::class, 'search'])->name('attendance.search');
    Route::get('/daily-attendance/{attendanceRecord}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/daily-attendance/{attendanceRecord}', [AttendanceController::class, 'update'])->name('attendance.update');

    Route::get('/face-enrollment', [FaceController::class, 'showEnrollForm'])->name('face.enroll');
    Route::post('/face-enrollment', [FaceController::class, 'storeEnrollForm'])->name('face.enroll.store');
    Route::post('/face-enrollment/reload', [FaceController::class, 'reloadFromFaces'])->name('face.enroll.reload');

    Route::get('/sheet-report/search', [ReportController::class, 'search'])->name('reports.sheet.search');
    Route::get('/sheet-report', [ReportController::class, 'index'])->name('reports.sheet');

    Route::resource('schedule', ScheduleController::class)->except(['show']);

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/search', [ActivityLogController::class, 'search'])->name('activity-logs.search');
});
