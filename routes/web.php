<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('manage-users')->name('manage-users.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->name('show');
        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
    });

    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/{position}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{position}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{position}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    Route::get('/daily-attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/daily-attendance/{attendanceRecord}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/daily-attendance/{attendanceRecord}', [AttendanceController::class, 'update'])->name('attendance.update');

    Route::get('/sheet-report', [ReportController::class, 'index'])->name('reports.sheet');
    Route::view('/schedule', 'schedule')->name('schedule.index');
    Route::view('/schedule/add', 'schedule-add')->name('schedule.create');
    Route::view('/schedule/edit', 'schedule-edit')->name('schedule.edit');
});
