<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['code' => 'HRD', 'name' => 'Human Resources Development', 'description' => 'Mengelola data pegawai dan kebijakan SDM'],
            ['code' => 'FIN', 'name' => 'Finance', 'description' => 'Mengelola arus kas dan laporan keuangan'],
            ['code' => 'MKT', 'name' => 'Marketing', 'description' => 'Strategi pemasaran dan brand awareness'],
            ['code' => 'PRD', 'name' => 'Production', 'description' => 'Operasional produksi dan pengawasan kualitas'],
            ['code' => 'IT',  'name' => 'Information Technology', 'description' => 'Pengembangan dan pemeliharaan sistem informasi'],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['code' => $department['code']],
                ['name' => $department['name'], 'description' => $department['description']]
            );
        }
    }
}
