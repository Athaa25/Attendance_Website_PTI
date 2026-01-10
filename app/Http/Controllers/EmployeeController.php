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
use Illuminate\Support\Facades\Http;

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
        $employee->setAttribute('face_photos', $this->getFacePhotos($employee->full_name));

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

    public function deleteFacePhotos(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['string'],
        ]);

        $slug = $this->slugPerson($employee->full_name);
        $dir = public_path('face-enrollments/' . $slug);

        if (! is_dir($dir)) {
            return back()->withErrors(['face_photos' => 'Folder foto wajah tidak ditemukan.']);
        }

        $existingFiles = $this->listFacePhotoFiles($dir);
        if (empty($existingFiles)) {
            return back()->withErrors(['face_photos' => 'Belum ada foto wajah tersimpan.']);
        }

        $selected = array_unique(array_map('basename', $validated['photos']));
        $selected = array_values(array_filter($selected, fn ($name) => $name !== ''));
        $selected = array_values(array_intersect($existingFiles, $selected));

        if (empty($selected)) {
            return back()->withErrors(['face_photos' => 'Foto yang dipilih tidak ditemukan.']);
        }

        $fastapiBase = trim((string) config('attendance.fastapi_url', env('FASTAPI_URL', '')));
        if ($fastapiBase === '') {
            return back()->withErrors(['face_photos' => 'FASTAPI_URL belum diset.']);
        }

        $deleteResult = $this->deleteFaceEmbeddings($employee->full_name, $fastapiBase);
        if (! ($deleteResult['success'] ?? false)) {
            return back()->withErrors(['face_photos' => $deleteResult['error'] ?? 'Gagal menghapus embedding wajah.']);
        }

        $deletedCount = 0;
        foreach ($selected as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_file($path) && @unlink($path)) {
                $deletedCount++;
            }
        }

        $remainingFiles = $this->listFacePhotoFiles($dir);
        if (empty($remainingFiles)) {
            @rmdir($dir);
        }

        $errors = [];
        foreach ($remainingFiles as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            $result = $this->enrollFacePhoto($employee->full_name, $path, $fastapiBase);
            if (! ($result['success'] ?? false)) {
                $errors[] = $result['error'] ?? 'Gagal enroll ulang foto wajah.';
            }
        }

        $status = $deletedCount > 0
            ? "Foto wajah terhapus: {$deletedCount}."
            : 'Tidak ada foto yang terhapus.';

        if (! empty($errors)) {
            return back()
                ->withErrors(['face_photos' => 'Sebagian embedding gagal diperbarui: ' . implode(' | ', array_unique($errors))])
                ->with('status', $status);
        }

        return back()->with('status', $status);
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

    private function attachFacePhotos($employees): void
    {
        foreach ($employees as $employee) {
            $employee->setAttribute('face_photos', $this->getFacePhotos($employee->full_name));
        }
    }

    private function getFacePhotos(string $name): array
    {
        $slug = $this->slugPerson($name);
        $dir = public_path('face-enrollments/' . $slug);

        $files = $this->listFacePhotoFiles($dir);
        if (empty($files)) {
            return [];
        }

        return array_map(function (string $file) use ($slug) {
            return [
                'name' => $file,
                'url' => asset('face-enrollments/' . $slug . '/' . $file),
            ];
        }, $files);
    }

    private function listFacePhotoFiles(string $dir): array
    {
        if (! is_dir($dir)) {
            return [];
        }

        $files = [];
        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..' || str_starts_with($file, '.')) {
                continue;
            }

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                continue;
            }

            $files[] = $file;
        }

        sort($files);

        return $files;
    }

    private function slugPerson(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9\-_. ]+/', '', $slug);
        $slug = preg_replace('/\s+/', '_', $slug);

        return $slug !== '' ? $slug : 'user_' . time();
    }

    private function deleteFaceEmbeddings(string $name, string $fastapiBase): array
    {
        $fastapiUrl = rtrim($fastapiBase, '/') . '/delete-embeddings';

        try {
            $resp = Http::withHeaders($this->fastApiHeaders($fastapiBase))
                ->connectTimeout(5)
                ->timeout(20)
                ->post($fastapiUrl, [
                    'name' => $name,
                ]);
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }

        $json = $resp->json();
        if (! $resp->successful() || !($json['success'] ?? false)) {
            return [
                'success' => false,
                'error' => $json['message'] ?? 'FastAPI gagal menghapus embedding.',
            ];
        }

        return ['success' => true];
    }

    private function enrollFacePhoto(string $name, string $path, string $fastapiBase): array
    {
        if (! is_file($path)) {
            return ['success' => false, 'error' => basename($path) . ': file tidak ditemukan.'];
        }

        $fastapiUrl = rtrim($fastapiBase, '/') . '/enroll';
        $headers = $this->fastApiHeaders($fastapiBase);
        $fileName = basename($path);
        $stream = fopen($path, 'r');
        if ($stream === false) {
            return ['success' => false, 'error' => "{$fileName}: gagal membaca file."];
        }

        $mime = function_exists('mime_content_type') ? mime_content_type($path) : null;
        $mime = $mime ?: 'image/jpeg';

        try {
            $resp = Http::withHeaders($headers)
                ->connectTimeout(5)
                ->timeout(45)
                ->attach('image', $stream, $fileName, [
                    'Content-Type' => $mime,
                ])
                ->post($fastapiUrl, [
                    'name' => $name,
                ]);
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => "{$fileName}: {$e->getMessage()}"];
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        $json = $resp->json();
        if (! is_array($json)) {
            $json = [];
        }
        if (! $resp->successful() || !($json['success'] ?? false)) {
            $raw = trim((string) $resp->body());
            $raw = $raw !== '' ? mb_substr($raw, 0, 200) : null;
            return [
                'success' => false,
                'error' => "{$fileName}: " . ($json['message']
                    ?? ($raw ? "FastAPI error: {$raw}" : 'Gagal enroll ulang.')),
            ];
        }

        return ['success' => true];
    }

    private function fastApiHeaders(string $fastapiBase): array
    {
        $host = parse_url($fastapiBase, PHP_URL_HOST);
        if (is_string($host) && str_contains($host, 'ngrok')) {
            return ['ngrok-skip-browser-warning' => 'true'];
        }

        return [];
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
