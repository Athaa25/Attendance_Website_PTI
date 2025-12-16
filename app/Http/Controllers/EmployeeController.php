<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
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
            'roleOptions' => $this->roleOptions(),
            'filters' => [
                'search' => $search,
                'department_id' => $departmentId,
                'status' => $status,
            ],
        ]);
    }

    public function search(Request $request): JsonResponse
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

        $suggestions = $employees->getCollection()
            ->take(7)
            ->map(function (Employee $employee) {
                return [
                    'id' => $employee->id,
                    'label' => $employee->full_name,
                    'department' => $employee->department?->name,
                    'position' => $employee->position?->name,
                    'term' => $employee->full_name,
                ];
            })
            ->values();

        return response()->json([
            'html' => view('employees.partials.table', [
                'employees' => $employees,
            ])->render(),
            'suggestions' => $suggestions,
        ]);
    }

    public function create()
    {
        return view('employees.create', [
            'departments' => Department::orderBy('name')->get(),
            'positions' => Position::with('department')->orderBy('name')->get(),
            'schedules' => Schedule::orderBy('name')->get(),
            'statusOptions' => Employee::employmentStatusOptions(),
            'roleOptions' => $this->roleOptions(),
        ]);
    }

    public function store(StoreEmployeeRequest $request)
    {
        $validated = $request->validated();

        $employee = DB::transaction(function () use ($validated) {
            $role = Role::query()->where('slug', $validated['role'])->firstOrFail();

            $user = User::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'username' => $validated['username'],
                'role_id' => $role->id,
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
                'nik',
                'nip',
                'telepon',
                'alamat',
                'tanggal_lahir',
                'tanggal_mulai',
                'order_date',
                'jenis_kelamin',
            ]);

            $employeeData['user_id'] = $user->id;
            $employeeData['work_email'] = $employeeData['work_email'] ?? $validated['email'];
            $employeeData['telepon'] = $employeeData['telepon'] ?? $employeeData['phone'];
            $employeeData['phone'] = $employeeData['phone'] ?? $employeeData['telepon'];
            $employeeData['alamat'] = $employeeData['alamat'] ?? $employeeData['address'];
            $employeeData['address'] = $employeeData['address'] ?? $employeeData['alamat'];
            $employeeData['tanggal_lahir'] = $employeeData['tanggal_lahir'] ?? $employeeData['date_of_birth'];
            $employeeData['date_of_birth'] = $employeeData['date_of_birth'] ?? $employeeData['tanggal_lahir'];
            $employeeData['tanggal_mulai'] = $employeeData['tanggal_mulai'] ?? $employeeData['hire_date'];
            $employeeData['hire_date'] = $employeeData['hire_date'] ?? $employeeData['tanggal_mulai'];
            $employeeData['order_date'] = $employeeData['order_date'] ?? $employeeData['hire_date'];
            $employeeData['gender'] = $employeeData['gender'] ?? $this->mapJenisKelaminToGender($employeeData['jenis_kelamin'] ?? null);

            return Employee::create($employeeData);
        });

        ActivityLogger::log(
            'create',
            $employee,
            "Pegawai {$employee->full_name} ditambahkan"
        );

        return redirect()->route('manage-users.index')
            ->with('status', 'Pegawai berhasil ditambahkan.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['department', 'position', 'schedule', 'user']);

        return view('employees.show', [
            'employee' => $employee,
            'statusOptions' => Employee::employmentStatusOptions(),
            'roleOptions' => $this->roleOptions(),
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
            'roleOptions' => $this->roleOptions(),
        ]);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $employee) {
            $role = Role::query()->where('slug', $validated['role'])->firstOrFail();

            $userData = Arr::only($validated, ['email', 'username', 'full_name']);
            $userData['role_id'] = $role->id;

            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $employee->user->update(array_merge(
                ['name' => $validated['full_name']],
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
                'nik',
                'nip',
                'telepon',
                'alamat',
                'tanggal_lahir',
                'tanggal_mulai',
                'order_date',
                'jenis_kelamin',
            ]);

            $employeeData['work_email'] = $employeeData['work_email'] ?? $validated['email'];
            $employeeData['telepon'] = $employeeData['telepon'] ?? $employeeData['phone'] ?? $employee->telepon;
            $employeeData['phone'] = $employeeData['phone'] ?? $employeeData['telepon'];
            $employeeData['alamat'] = $employeeData['alamat'] ?? $employeeData['address'] ?? $employee->alamat;
            $employeeData['address'] = $employeeData['address'] ?? $employeeData['alamat'];
            $employeeData['tanggal_lahir'] = $employeeData['tanggal_lahir'] ?? $employeeData['date_of_birth'] ?? $employee->tanggal_lahir;
            $employeeData['date_of_birth'] = $employeeData['date_of_birth'] ?? $employeeData['tanggal_lahir'];
            $employeeData['tanggal_mulai'] = $employeeData['tanggal_mulai'] ?? $employeeData['hire_date'] ?? $employee->tanggal_mulai;
            $employeeData['hire_date'] = $employeeData['hire_date'] ?? $employeeData['tanggal_mulai'];
            $employeeData['order_date'] = $employeeData['order_date'] ?? $employeeData['hire_date'] ?? $employee->order_date;
            $employeeData['gender'] = $employeeData['gender'] ?? $this->mapJenisKelaminToGender($employeeData['jenis_kelamin'] ?? null) ?? $employee->gender;

            $employee->update($employeeData);
        });

        ActivityLogger::log(
            'update',
            $employee,
            "Data pegawai {$employee->full_name} diperbarui"
        );

        return redirect()->route('manage-users.show', $employee)
            ->with('status', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        $employeeName = $employee->full_name;
        DB::transaction(function () use ($employee) {
            $user = $employee->user;
            $employee->delete();
            $user?->delete();
        });

        ActivityLogger::log(
            'delete',
            $employee,
            "Pegawai {$employeeName} dihapus"
        );

        return redirect()->route('manage-users.index')
            ->with('status', 'Pegawai berhasil dihapus.');
    }

    private function roleOptions(): array
    {
        return Role::query()
            ->orderBy('name')
            ->get()
            ->pluck('name', 'slug')
            ->toArray();
    }

    private function mapJenisKelaminToGender(?int $value): ?string
    {
        return match ($value) {
            1 => 'male',
            0 => 'female',
            default => null,
        };
    }
}
