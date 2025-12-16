<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Position;
use App\Services\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $positions = Position::with('department')
            ->get()
            ->sortBy(function (Position $position) {
                $departmentName = Str::lower((string) ($position->department->name ?? ''));
                $positionName = Str::lower((string) ($position->name ?? ''));

                return $departmentName . '|' . $positionName;
            })
            ->values();

        return view('departments.index', [
            'positions' => $positions,
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $searchTerm = Str::of($request->query('q', ''))->squish()->toString();
        $positionsQuery = Position::with('department');

        if ($searchTerm !== '') {
            $normalized = Str::lower($searchTerm);

            $positionsQuery->where(function ($query) use ($normalized) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$normalized}%"])
                    ->orWhereHas('department', function ($departmentQuery) use ($normalized) {
                        $departmentQuery->whereRaw('LOWER(name) LIKE ?', ["%{$normalized}%"]);
                    });
            });
        }

        $positions = $positionsQuery
            ->get()
            ->sortBy(function (Position $position) {
                $departmentName = Str::lower((string) ($position->department->name ?? ''));
                $positionName = Str::lower((string) ($position->name ?? ''));

                return $departmentName . '|' . $positionName;
            })
            ->values();

        $suggestions = $positions
            ->take(7)
            ->map(function (Position $position) {
                $departmentName = $position->department->name ?? 'Tanpa Departemen';

                return [
                    'id' => $position->id,
                    'position' => $position->name,
                    'department' => $position->department?->name,
                    'label' => trim(($position->name ? $position->name . ' · ' : '') . $departmentName),
                    'term' => $position->name ?: $departmentName,
                ];
            })
            ->values();

        return response()->json([
            'html' => view('departments.partials.positions', [
                'positions' => $positions,
            ])->render(),
            'suggestions' => $suggestions,
        ]);
    }

    public function create(): View
    {
        return view('departments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'department_name' => ['required', 'string', 'max:255'],
            'position_name' => ['required', 'string', 'max:255'],
        ]);

        $departmentName = Str::of($validated['department_name'])->squish()->toString();
        $positionName = Str::of($validated['position_name'])->squish()->toString();

        [$department, $position] = DB::transaction(function () use ($departmentName, $positionName) {
            $department = Department::query()
                ->whereRaw('LOWER(name) = ?', [Str::lower($departmentName)])
                ->first();

            if (! $department) {
                $department = Department::create([
                    'code' => $this->generateDepartmentCode($departmentName),
                    'name' => $departmentName,
                ]);
            }

            $position = Position::create([
                'department_id' => $department->id,
                'name' => $positionName,
            ]);

            return [$department, $position];
        });

        ActivityLogger::log(
            'create',
            $position,
            "Jabatan {$positionName} pada departemen {$departmentName} ditambahkan"
        );

        return redirect()
            ->route('departments.index')
            ->with('success', 'Departemen & jabatan baru berhasil ditambahkan.');
    }

    public function edit(Position $position): View
    {
        $position->load('department');

        return view('departments.edit', [
            'position' => $position,
        ]);
    }

    public function update(Request $request, Position $position): RedirectResponse
    {
        $validated = $request->validate([
            'department_name' => ['required', 'string', 'max:255'],
            'position_name' => ['required', 'string', 'max:255'],
        ]);

        $departmentName = Str::of($validated['department_name'])->squish()->toString();
        $positionName = Str::of($validated['position_name'])->squish()->toString();

        DB::transaction(function () use ($departmentName, $positionName, $position) {
            $department = $position->department;

            if ($department) {
                $department->update([
                    'name' => $departmentName,
                ]);
            } else {
                $department = Department::create([
                    'code' => $this->generateDepartmentCode($departmentName),
                    'name' => $departmentName,
                ]);

                $position->department()->associate($department);
            }

            $position->name = $positionName;
            $position->save();
        });

        ActivityLogger::log(
            'update',
            $position,
            "Jabatan {$positionName} pada departemen {$departmentName} diperbarui"
        );

        return redirect()
            ->route('departments.index')
            ->with('success', 'Departemen & jabatan berhasil diperbarui.');
    }

    public function destroy(Position $position): RedirectResponse
    {
        $positionName = $position->name;
        $departmentName = $position->department?->name;

        DB::transaction(function () use ($position) {
            $department = $position->department;

            $position->delete();

            if ($department && $department->positions()->count() === 0) {
                $department->delete();
            }
        });

        ActivityLogger::log(
            'delete',
            $position,
            "Jabatan {$positionName}" . ($departmentName ? " pada departemen {$departmentName}" : '') . ' dihapus'
        );

        return redirect()
            ->route('departments.index')
            ->with('success', 'Data jabatan berhasil dihapus.');
    }

    private function generateDepartmentCode(string $departmentName): string
    {
        $normalized = Str::upper(Str::ascii($departmentName));
        $normalized = preg_replace('/[^A-Z0-9]/u', '', $normalized ?? '');
        $base = substr($normalized, 0, 5);

        if ($base === '') {
            $base = 'DEPT';
        }

        $code = $base;
        $counter = 1;

        while (Department::where('code', $code)->exists()) {
            $code = $base . $counter;
            $counter++;
        }

        return $code;
    }
}
