<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // Kolom yang bisa diisi mass assignment
    protected $fillable = ['name', 'position'];
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
