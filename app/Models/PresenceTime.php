<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresenceTime extends Model
{
    /** @use HasFactory<\Database\Factories\PresenceTimeFactory> */
    use HasFactory;

    public const TYPE_CHECK_IN = 'check_in';
    public const TYPE_CHECK_OUT = 'check_out';
    public const TYPE_MANUAL = 'manual';

    protected $fillable = [
        'employee_id',
        'type',
        'checkin_time',
        'checkout_time',
        'recorded_at',
        'notes',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeCheckIns($query)
    {
        return $query->where('type', self::TYPE_CHECK_IN);
    }

    public function scopeCheckOuts($query)
    {
        return $query->where('type', self::TYPE_CHECK_OUT);
    }
}
