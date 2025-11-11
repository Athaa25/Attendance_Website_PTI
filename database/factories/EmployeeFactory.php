<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Position;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);
        $dateOfBirth = fake()->dateTimeBetween('-45 years', '-22 years');
        $hireDate = fake()->dateTimeBetween('-5 years', 'now');
        $employeeCode = strtoupper('EMP-' . fake()->unique()->numerify('####'));
        $phone = fake()->phoneNumber();
        $address = fake()->address();

        return [
            'user_id' => User::factory(),
            'employee_code' => $employeeCode,
            'full_name' => fake()->name($gender),
            'gender' => $gender,
            'phone' => $phone,
            'work_email' => fake()->unique()->safeEmail(),
            'national_id' => fake()->unique()->numerify('################'),
            'place_of_birth' => fake()->city(),
            'date_of_birth' => $dateOfBirth,
            'hire_date' => $hireDate,
            'employment_status' => fake()->randomElement(['active', 'probation', 'inactive']),
            'salary' => fake()->numberBetween(3500000, 15000000),
            'address' => $address,
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
            'schedule_id' => Schedule::factory(),
            'nik' => $employeeCode,
            'nip' => fake()->unique()->numerify('################'),
            'telepon' => $phone,
            'alamat' => $address,
            'tanggal_lahir' => $dateOfBirth,
            'tanggal_mulai' => $hireDate,
            'order_date' => $hireDate,
            'jenis_kelamin' => $gender === 'male' ? 1 : 0,
        ];
    }
}
