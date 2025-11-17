<?php

namespace Database\Seeders;

use App\Models\AttendanceReason;
use App\Models\AttendanceStatus;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ReferenceDataSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['slug' => 'super-admin', 'name' => 'Super Admin', 'description' => 'Akses penuh sistem'],
            ['slug' => 'admin', 'name' => 'Administrator', 'description' => 'Kelola master data & laporan'],
            ['slug' => 'hr', 'name' => 'HR / Personalia', 'description' => 'Kelola data pegawai dan absensi'],
            ['slug' => 'employee', 'name' => 'Karyawan', 'description' => 'Pengguna umum'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                ['name' => $role['name'], 'description' => $role['description']]
            );
        }

        $statuses = [
            ['code' => 'present', 'label' => 'Hadir', 'description' => 'Hadir tepat waktu', 'is_late' => false, 'requires_reason' => false],
            ['code' => 'late', 'label' => 'Terlambat', 'description' => 'Hadir namun terlambat', 'is_late' => true, 'requires_reason' => false],
            ['code' => 'leave', 'label' => 'Izin', 'description' => 'Izin resmi', 'is_late' => false, 'requires_reason' => true],
            ['code' => 'sick', 'label' => 'Sakit', 'description' => 'Tidak masuk karena sakit', 'is_late' => false, 'requires_reason' => true],
            ['code' => 'absent', 'label' => 'Alpa', 'description' => 'Tidak hadir tanpa keterangan', 'is_late' => false, 'requires_reason' => false],
        ];

        foreach ($statuses as $status) {
            AttendanceStatus::updateOrCreate(
                ['code' => $status['code']],
                [
                    'label' => $status['label'],
                    'description' => $status['description'],
                    'is_late' => $status['is_late'],
                    'requires_reason' => $status['requires_reason'],
                ]
            );
        }

        $reasons = [
            ['code' => 'dinas_diluar', 'label' => 'Dinas di luar kantor', 'description' => 'Tugas dinas di luar kantor'],
            ['code' => 'sakit', 'label' => 'Sakit', 'description' => 'Tidak hadir karena sakit'],
            ['code' => 'alpa', 'label' => 'Tanpa keterangan', 'description' => 'Absen tanpa alasan jelas'],
            ['code' => 'other', 'label' => 'Alasan Lainnya', 'description' => 'Alasan lain yang tidak terdaftar'],
        ];

        foreach ($reasons as $reason) {
            AttendanceReason::updateOrCreate(
                ['code' => $reason['code']],
                ['label' => $reason['label'], 'description' => $reason['description']]
            );
        }
    }
}
