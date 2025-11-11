<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View
    {
        $schedules = Schedule::query()
            ->orderBy('start_time')
            ->orderBy('name')
            ->get();

        return view('schedule.index', [
            'schedules' => $schedules,
        ]);
    }

    public function create(): View
    {
        return view('schedule.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:schedules,code'],
            'name' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'description' => ['nullable', 'string'],
        ]);

        Schedule::create($validated);

        return redirect()
            ->route('schedule.index')
            ->with('status', 'Shift baru berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule): View
    {
        return view('schedule.edit', [
            'schedule' => $schedule,
        ]);
    }

    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:schedules,code,' . $schedule->id],
            'name' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'description' => ['nullable', 'string'],
        ]);

        $schedule->update($validated);

        return redirect()
            ->route('schedule.index')
            ->with('status', 'Data shift berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return redirect()
            ->route('schedule.index')
            ->with('status', 'Data shift berhasil dihapus.');
    }
}
