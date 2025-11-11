<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $gender = $this->input('gender');
        $jenisKelamin = $this->input('jenis_kelamin');

        $this->merge([
            'employee_code' => strtoupper($this->input('employee_code')),
            'username' => strtolower($this->input('username')),
            'work_email' => $this->input('work_email') ?: $this->input('email'),
            'nik' => strtoupper($this->input('nik') ?: $this->input('employee_code')),
            'national_id' => $this->input('national_id') ?: $this->input('nik') ?: $this->input('employee_code'),
            'nip' => $this->input('nip') ?: $this->input('national_id'),
            'telepon' => $this->input('telepon') ?: $this->input('phone'),
            'alamat' => $this->input('alamat') ?: $this->input('address'),
            'tanggal_mulai' => $this->input('tanggal_mulai') ?: $this->input('hire_date'),
            'tanggal_lahir' => $this->input('tanggal_lahir') ?: $this->input('date_of_birth'),
            'jenis_kelamin' => $jenisKelamin ?? $this->mapGenderToInteger($gender),
            'order_date' => $this->input('order_date') ?: $this->input('hire_date'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Employee $employee */
        $employee = $this->route('employee');
        $userId = $employee?->user_id;

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'employee_code' => ['required', 'string', 'max:50', Rule::unique('employees', 'employee_code')->ignore($employee?->id)],
            'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($userId)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'work_email' => ['nullable', 'email', 'max:255', Rule::unique('employees', 'work_email')->ignore($employee?->id)],
            'role' => ['required', Rule::exists('roles', 'slug')],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:30'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'jenis_kelamin' => ['nullable', 'integer', 'in:0,1'],
            'national_id' => ['nullable', 'string', 'max:32'],
            'nik' => ['required', 'string', 'max:50', Rule::unique('employees', 'nik')->ignore($employee?->id)],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('employees', 'nip')->ignore($employee?->id)],
            'place_of_birth' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string'],
            'alamat' => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'hire_date' => ['nullable', 'date'],
            'tanggal_mulai' => ['nullable', 'date'],
            'order_date' => ['nullable', 'date'],
            'employment_status' => ['required', Rule::in(array_keys(Employee::employmentStatusOptions()))],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'schedule_id' => ['required', 'exists:schedules,id'],
        ];
    }

    private function mapGenderToInteger(?string $gender): ?int
    {
        return match ($gender) {
            'male' => 1,
            'female' => 0,
            default => null,
        };
    }
}
