<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceRecordFactory> */
    use HasFactory;

    public const STATUS_PRESENT = 'present';
    public const STATUS_LATE = 'late';
    public const STATUS_LEAVE = 'leave';
    public const STATUS_SICK = 'sick';
    public const STATUS_ABSENT = 'absent';

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'status',
        'check_in_time',
        'check_out_time',
        'late_minutes',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_time' => 'datetime:H:i',
        'check_out_time' => 'datetime:H:i',
    ];

    protected $appends = [
        'status_label',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    public function scopeInRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attendance_date', [$startDate, $endDate]);
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PRESENT => 'Hadir',
            self::STATUS_LATE => 'Terlambat',
            self::STATUS_LEAVE => 'Izin',
            self::STATUS_SICK => 'Sakit',
            self::STATUS_ABSENT => 'Alpa',
        ];
    }

    public static function statusBadgeClasses(): array
    {
        return [
            self::STATUS_PRESENT => 'status-present',
            self::STATUS_LATE => 'status-late',
            self::STATUS_LEAVE => 'status-leave',
            self::STATUS_SICK => 'status-sick',
            self::STATUS_ABSENT => 'status-absent',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return static::statusLabels()[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return static::statusBadgeClasses()[$this->status] ?? 'status-unknown';
    }
}
