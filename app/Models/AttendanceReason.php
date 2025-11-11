<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceReason extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceReasonFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'label',
        'description',
    ];

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'reason_id');
    }

    public static function findByCode(?string $code): ?self
    {
        if (! $code) {
            return null;
        }

        return static::query()->where('code', $code)->first();
    }
}
