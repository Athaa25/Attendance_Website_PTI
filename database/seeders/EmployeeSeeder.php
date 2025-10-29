<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all()->keyBy('code');
        $positions = Position::all()->keyBy('name');
        $schedules = Schedule::all()->keyBy('code');

        $employees = [
            [
                'user' => [
                    'name' => 'Akbar Prabo',
                    'email' => 'akbar.prabo@rmdi.id',
                    'username' => 'akbarprabo',
                    'role' => 'admin',
                    'password' => 'password',
                ],
                'employee' => [
                    'employee_code' => 'EMP-0001',
                    'full_name' => 'Akbar Prabo',
                    'gender' => 'male',
                    'phone' => '0812-3456-7890',
                    'work_email' => 'akbar.prabo@rmdi.id',
                    'national_id' => '3571720201010101',
                    'place_of_birth' => 'Malang',
                    'date_of_birth' => '1990-04-21',
                    'hire_date' => '2015-01-05',
                    'employment_status' => 'active',
                    'salary' => 9500000,
                    'address' => 'Jl. Anggrek No. 12, Malang',
                    'department_code' => 'HRD',
                    'position_name' => 'HR Manager',
                    'schedule_code' => 'SHIFT-2',
                ],
            ],
            [
                'user' => [
                    'name' => 'Fefe Fifi Fufu Fafa',
                    'email' => 'fefe.fafa@rmdi.id',
                    'username' => 'fefe.fafa',
                    'role' => 'employee',
                    'password' => 'password',
                ],
                'employee' => [
                    'employee_code' => 'EMP-0002',
                    'full_name' => 'Fefe Fifi Fufu Fafa',
                    'gender' => 'female',
                    'phone' => '0813-5566-7788',
                    'work_email' => 'fefe.fafa@rmdi.id',
                    'national_id' => '3571720202020202',
                    'place_of_birth' => 'Surabaya',
                    'date_of_birth' => '1996-09-12',
                    'hire_date' => '2021-02-14',
                    'employment_status' => 'active',
                    'salary' => 6800000,
                    'address' => 'Perum Puncak Indah Blok C2, Surabaya',
                    'department_code' => 'MKT',
                    'position_name' => 'Marketing Specialist',
                    'schedule_code' => 'SHIFT-2',
                ],
            ],
            [
                'user' => [
                    'name' => 'Rio Hu',
                    'email' => 'rio.hu@rmdi.id',
                    'username' => 'rio.hu',
                    'role' => 'employee',
                    'password' => 'password',
                ],
                'employee' => [
                    'employee_code' => 'EMP-0003',
                    'full_name' => 'Rio Hu',
                    'gender' => 'male',
                    'phone' => '0814-9988-7766',
                    'work_email' => 'rio.hu@rmdi.id',
                    'national_id' => '3571720203030303',
                    'place_of_birth' => 'Gresik',
                    'date_of_birth' => '1995-05-04',
                    'hire_date' => '2020-07-01',
                    'employment_status' => 'active',
                    'salary' => 7200000,
                    'address' => 'Jl. Rajawali No. 45, Gresik',
                    'department_code' => 'FIN',
                    'position_name' => 'Finance Analyst',
                    'schedule_code' => 'SHIFT-2',
                ],
            ],
            [
                'user' => [
                    'name' => 'Pepet Siebor',
                    'email' => 'pepet.siebor@rmdi.id',
                    'username' => 'pepet.siebor',
                    'role' => 'employee',
                    'password' => 'password',
                ],
                'employee' => [
                    'employee_code' => 'EMP-0004',
                    'full_name' => 'Pepet Siebor',
                    'gender' => 'male',
                    'phone' => '0815-4455-2211',
                    'work_email' => 'pepet.siebor@rmdi.id',
                    'national_id' => '3571720204040404',
                    'place_of_birth' => 'Sidoarjo',
                    'date_of_birth' => '1992-03-17',
                    'hire_date' => '2018-03-10',
                    'employment_status' => 'active',
                    'salary' => 7800000,
                    'address' => 'Jl. Kenjeran No. 78, Sidoarjo',
                    'department_code' => 'PRD',
                    'position_name' => 'Production Supervisor',
                    'schedule_code' => 'SHIFT-1',
                ],
            ],
            [
                'user' => [
                    'name' => 'Mie Ayam Gedangan',
                    'email' => 'mie.gedangan@rmdi.id',
                    'username' => 'mie.gedangan',
                    'role' => 'employee',
                    'password' => 'password',
                ],
                'employee' => [
                    'employee_code' => 'EMP-0005',
                    'full_name' => 'Mie Ayam Gedangan',
                    'gender' => 'female',
                    'phone' => '0816-4455-6677',
                    'work_email' => 'mie.gedangan@rmdi.id',
                    'national_id' => '3571720205050505',
                    'place_of_birth' => 'Kediri',
                    'date_of_birth' => '1998-11-24',
                    'hire_date' => '2022-06-18',
                    'employment_status' => 'probation',
                    'salary' => 5600000,
                    'address' => 'Jl. Mawar No. 5, Kediri',
                    'department_code' => 'PRD',
                    'position_name' => 'Quality Control',
                    'schedule_code' => 'SHIFT-1',
                ],
            ],
            [
                'user' => [
                    'name' => 'Hasan Susanto',
                    'email' => 'hasan.susanto@rmdi.id',
                    'username' => 'hasan.susanto',
                    'role' => 'employee',
                    'password' => 'password',
                ],
                'employee' => [
                    'employee_code' => 'EMP-0006',
                    'full_name' => 'Hasan Susanto',
                    'gender' => 'male',
                    'phone' => '0817-8899-5544',
                    'work_email' => 'hasan.susanto@rmdi.id',
                    'national_id' => '3571720206060606',
                    'place_of_birth' => 'Lamongan',
                    'date_of_birth' => '1994-12-09',
                    'hire_date' => '2019-09-02',
                    'employment_status' => 'active',
                    'salary' => 6000000,
                    'address' => 'Jl. Cendana No. 8, Lamongan',
                    'department_code' => 'IT',
                    'position_name' => 'IT Support',
                    'schedule_code' => 'SHIFT-2',
                ],
            ],
            [
                'user' => [
                    'name' => 'Kim Kim Kim',
                    'email' => 'kim.kim@rmdi.id',
                    'username' => 'kim.kim',
                    'role' => 'hr',
                    'password' => 'password',
                ],
                'employee' => [
                    'employee_code' => 'EMP-0007',
                    'full_name' => 'Kim Kim Kim',
                    'gender' => 'male',
                    'phone' => '0818-2211-3344',
                    'work_email' => 'kim.kim@rmdi.id',
                    'national_id' => '3571720207070707',
                    'place_of_birth' => 'Wonosobo',
                    'date_of_birth' => '1991-01-03',
                    'hire_date' => '2017-01-15',
                    'employment_status' => 'active',
                    'salary' => 8000000,
                    'address' => 'RT 01 RW 01 Wonosobo, Jawa Tengah',
                    'department_code' => 'HRD',
                    'position_name' => 'HR Staff',
                    'schedule_code' => 'SHIFT-2',
                ],
            ],
        ];

        foreach ($employees as $record) {
            $userData = $record['user'];
            $password = $userData['password'] ?? 'password';
            unset($userData['password']);

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                ])
            );

            $employeeData = $record['employee'];
            $department = $departments[$employeeData['department_code']] ?? null;
            $position = $positions[$employeeData['position_name']] ?? null;
            $schedule = $schedules[$employeeData['schedule_code']] ?? null;

            Employee::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_code' => $employeeData['employee_code'],
                    'full_name' => $employeeData['full_name'],
                    'gender' => $employeeData['gender'],
                    'phone' => $employeeData['phone'],
                    'work_email' => $employeeData['work_email'],
                    'national_id' => $employeeData['national_id'],
                    'place_of_birth' => $employeeData['place_of_birth'],
                    'date_of_birth' => $employeeData['date_of_birth'],
                    'hire_date' => $employeeData['hire_date'],
                    'employment_status' => $employeeData['employment_status'],
                    'salary' => $employeeData['salary'],
                    'address' => $employeeData['address'],
                    'department_id' => $department?->id,
                    'position_id' => $position?->id,
                    'schedule_id' => $schedule?->id,
                ]
            );
        }
    }
}
