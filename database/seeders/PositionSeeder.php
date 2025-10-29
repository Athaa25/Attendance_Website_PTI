<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            'HRD' => [
                ['name' => 'HR Manager', 'base_salary' => 9000000, 'description' => 'Bertanggung jawab atas kebijakan SDM'],
                ['name' => 'HR Staff', 'base_salary' => 5500000, 'description' => 'Pengelolaan administrasi karyawan'],
            ],
            'FIN' => [
                ['name' => 'Finance Analyst', 'base_salary' => 7200000, 'description' => 'Analisis laporan keuangan bulanan'],
                ['name' => 'Accountant', 'base_salary' => 6800000, 'description' => 'Mencatat transaksi dan membuat jurnal'],
            ],
            'MKT' => [
                ['name' => 'Marketing Specialist', 'base_salary' => 6500000, 'description' => 'Menyusun strategi kampanye pemasaran'],
                ['name' => 'Content Creator', 'base_salary' => 5200000, 'description' => 'Produksi konten digital dan sosial media'],
            ],
            'PRD' => [
                ['name' => 'Production Supervisor', 'base_salary' => 7800000, 'description' => 'Mengawasi proses produksi'],
                ['name' => 'Quality Control', 'base_salary' => 6000000, 'description' => 'Menjaga standar kualitas produk'],
            ],
            'IT' => [
                ['name' => 'IT Support', 'base_salary' => 5800000, 'description' => 'Menangani kebutuhan support harian'],
                ['name' => 'Full Stack Developer', 'base_salary' => 8500000, 'description' => 'Mengembangkan aplikasi internal'],
            ],
        ];

        foreach ($positions as $departmentCode => $roles) {
            $department = Department::where('code', $departmentCode)->first();

            if (! $department) {
                continue;
            }

            foreach ($roles as $role) {
                Position::updateOrCreate(
                    [
                        'department_id' => $department->id,
                        'name' => $role['name'],
                    ],
                    [
                        'base_salary' => $role['base_salary'],
                        'description' => $role['description'],
                    ]
                );
            }
        }
    }
}
