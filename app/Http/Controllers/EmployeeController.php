<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    private const ROLE_OPTIONS = [
        'admin' => 'Administrator',
        'hr' => 'HR',
        'employee' => 'Karyawan',
    ];

    public function index(Request $request)
    {
        $query = Employee::with(['department', 'position', 'schedule', 'user']);
        $search = $request->string('search')->toString();
        $departmentId = $request->integer('department_id');
        $status = $request->string('status')->toString();

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('full_name', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($userQuery) => $userQuery->where('email', 'like', "%{$search}%"));
            });
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($status !== '') {
            $query->where('employment_status', $status);
        }

        $employees = $query->orderBy('full_name')->paginate(10)->withQueryString();

        return view('employees.index', [
            'employees' => $employees,
            'departments' => Department::orderBy('name')->get(),
            'statusOptions' => Employee::employmentStatusOptions(),
            'roleOptions' => self::ROLE_OPTIONS,
            'filters' => [
                'search' => $search,
                'department_id' => $departmentId,
                'status' => $status,
            ],
        ]);
    }

    public function create()
    {
        return view('employees.create', [
            'departments' => Department::orderBy('name')->get(),
            'positions' => Position::with('department')->orderBy('name')->get(),
            'schedules' => Schedule::orderBy('name')->get(),
            'statusOptions' => Employee::employmentStatusOptions(),
            'roleOptions' => self::ROLE_OPTIONS,
        ]);
    }

    public function store(StoreEmployeeRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'username' => $validated['username'],
                'role' => $validated['role'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(),
            ]);

            $employeeData = Arr::only($validated, [
                'employee_code',
                'full_name',
                'gender',
                'phone',
                'work_email',
                'national_id',
                'place_of_birth',
                'date_of_birth',
                'hire_date',
                'employment_status',
                'salary',
                'address',
                'department_id',
                'position_id',
                'schedule_id',
            ]);

            $employeeData['user_id'] = $user->id;
            $employeeData['work_email'] = $employeeData['work_email'] ?? $validated['email'];

            Employee::create($employeeData);
        });

        return redirect()->route('manage-users.index')
            ->with('status', 'Pegawai berhasil ditambahkan.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['department', 'position', 'schedule', 'user']);

        return view('employees.show', [
            'employee' => $employee,
            'statusOptions' => Employee::employmentStatusOptions(),
            'roleOptions' => self::ROLE_OPTIONS,
        ]);
    }

    public function edit(Employee $employee)
    {
        $employee->load(['department', 'position', 'schedule', 'user']);

        return view('employees.edit', [
            'employee' => $employee,
            'departments' => Department::orderBy('name')->get(),
            'positions' => Position::with('department')->orderBy('name')->get(),
            'schedules' => Schedule::orderBy('name')->get(),
            'statusOptions' => Employee::employmentStatusOptions(),
            'roleOptions' => self::ROLE_OPTIONS,
        ]);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $employee) {
            $userData = Arr::only($validated, ['email', 'username', 'role', 'full_name']);

            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $employee->user->update(array_merge(
                [
                    'name' => $validated['full_name'],
                ],
                Arr::except($userData, ['full_name'])
            ));

            $employeeData = Arr::only($validated, [
                'employee_code',
                'full_name',
                'gender',
                'phone',
                'work_email',
                'national_id',
                'place_of_birth',
                'date_of_birth',
                'hire_date',
                'employment_status',
                'salary',
                'address',
                'department_id',
                'position_id',
                'schedule_id',
            ]);

            $employee->update($employeeData);
        });

        return redirect()->route('manage-users.show', $employee)
            ->with('status', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        DB::transaction(function () use ($employee) {
            $user = $employee->user;
            $employee->delete();
            $user?->delete();
        });

        return redirect()->route('manage-users.index')
            ->with('status', 'Pegawai berhasil dihapus.');
    }
}
