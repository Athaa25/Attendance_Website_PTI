<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
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
        $this->merge([
            'employee_code' => strtoupper($this->input('employee_code')),
            'username' => strtolower($this->input('username')),
            'work_email' => $this->input('work_email') ?: $this->input('email'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'employee_code' => ['required', 'string', 'max:50', 'unique:employees,employee_code'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'work_email' => ['nullable', 'email', 'max:255', 'unique:employees,work_email'],
            'role' => ['required', Rule::in(['admin', 'hr', 'employee'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'national_id' => ['nullable', 'string', 'max:32'],
            'place_of_birth' => ['nullable', 'string', 'max:120'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'hire_date' => ['nullable', 'date'],
            'employment_status' => ['required', Rule::in(array_keys(Employee::employmentStatusOptions()))],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'address' => ['nullable', 'string'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'schedule_id' => ['required', 'exists:schedules,id'],
        ];
    }
}
