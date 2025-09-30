<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        // Ambil semua karyawan beserta attendances hari ini
        $employees = Employee::with(['attendances' => function($query) use ($today) {
            $query->whereDate('created_at', $today);
        }])->get();

        return view('dashboard', compact('employees'));
    }
}
