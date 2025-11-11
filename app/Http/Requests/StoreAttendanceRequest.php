<?php

namespace App\Http\Requests;

use App\Models\AttendanceRecord;
use App\Models\AttendanceReason;
use App\Models\AttendanceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceRequest extends FormRequest
{
    protected ?AttendanceStatus $statusDefinition = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $statusCode = $this->input('status');
        $this->statusDefinition = AttendanceStatus::query()->where('code', $statusCode)->first();
        $requiresReason = (bool) ($this->statusDefinition?->requires_reason);

        $this->merge([
            'status' => $this->statusDefinition?->code ?? $statusCode,
            'leave_reason' => $requiresReason ? $this->input('leave_reason') : null,
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
        $statusCodes = $this->availableStatusCodes();
        $reasonCodes = $this->availableReasonCodes();

        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'attendance_date' => [
                'required',
                'date',
                Rule::unique('attendance_records', 'attendance_date')
                    ->where(fn ($query) => $query->where('employee_id', $employeeId)),
            ],
            'status' => ['required', Rule::in($statusCodes)],
            'leave_reason' => [
                Rule::requiredIf(fn () => (bool) ($this->statusDefinition?->requires_reason)),
                'nullable',
                Rule::in($reasonCodes),
            ],
            'supporting_document' => [
                Rule::requiredIf(fn () => (bool) ($this->statusDefinition?->requires_reason)),
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

    /**
     * @return list<string>
     */
    private function availableStatusCodes(): array
    {
        $codes = AttendanceStatus::query()->pluck('code')->all();

        return ! empty($codes)
            ? $codes
            : array_keys(AttendanceRecord::statusLabels());
    }

    /**
     * @return list<string>
     */
    private function availableReasonCodes(): array
    {
        $codes = AttendanceReason::query()->pluck('code')->all();

        return ! empty($codes)
            ? $codes
            : array_keys(AttendanceRecord::leaveReasonOptions());
    }
}
