<?php

namespace App\Http\Requests;

use App\Models\AttendanceRecord;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceRequest extends FormRequest
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
            'leave_reason' => $this->input('status') === AttendanceRecord::STATUS_LEAVE
                ? $this->input('leave_reason')
                : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $employeeId = $this->input('employee_id');

        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'attendance_date' => [
                'required',
                'date',
                Rule::unique('attendance_records', 'attendance_date')
                    ->where(fn ($query) => $query->where('employee_id', $employeeId)),
            ],
            'status' => ['required', Rule::in([AttendanceRecord::STATUS_PRESENT, AttendanceRecord::STATUS_LEAVE])],
            'leave_reason' => [
                Rule::requiredIf(fn () => $this->input('status') === AttendanceRecord::STATUS_LEAVE),
                'nullable',
                Rule::in(array_keys(AttendanceRecord::leaveReasonOptions())),
            ],
            'supporting_document' => [
                Rule::requiredIf(fn () => $this->input('status') === AttendanceRecord::STATUS_LEAVE),
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
            'check_in_time' => ['nullable', 'date_format:H:i'],
            'check_out_time' => ['nullable', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
