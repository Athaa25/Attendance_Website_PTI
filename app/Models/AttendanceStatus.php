<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceStatus extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceStatusFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'label',
        'description',
        'is_late',
        'requires_reason',
    ];

    protected $casts = [
        'is_late' => 'boolean',
        'requires_reason' => 'boolean',
    ];

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'status_id');
    }

    public static function findByCode(?string $code): ?self
    {
        if (! $code) {
            return null;
        }

        return static::query()->where('code', $code)->first();
    }
}
