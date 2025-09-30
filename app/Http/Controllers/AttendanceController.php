<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;   // ✅ ini model
use App\Models\Employee;     // ✅ kalau butuh relasi karyawan

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = \App\Models\Attendance::with('employee')->get();
        return view('attendances.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::all();
        return view('attendances.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'status' => 'required|in:ontime,late,absent',
            'reason' => 'nullable|in:sakit,ijin,alfa',
        ]);

        Attendance::create($request->all()); // ✅ pakai model Attendance

        return redirect()->route('attendances.index')->with('success', 'Attendance berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
