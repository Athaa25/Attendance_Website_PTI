<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_PROBATION = 'probation';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_CONTRACT = 'contract';
    public const STATUS_RESIGNED = 'resigned';

    protected $fillable = [
        'user_id',
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
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    protected $appends = [
        'employment_status_label',
    ];

    public static function employmentStatusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_PROBATION => 'Masa Percobaan',
            self::STATUS_CONTRACT => 'Kontrak',
            self::STATUS_INACTIVE => 'Non Aktif',
            self::STATUS_RESIGNED => 'Resign',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function getEmploymentStatusLabelAttribute(): string
    {
        return static::employmentStatusOptions()[$this->employment_status] ?? ucfirst($this->employment_status);
    }
}
