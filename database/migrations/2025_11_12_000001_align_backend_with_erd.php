<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('attendance_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('label');
            $table->string('description')->nullable();
            $table->boolean('is_late')->default(false);
            $table->boolean('requires_reason')->default(false);
            $table->timestamps();
        });

        Schema::create('attendance_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('label');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('presence_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['check_in', 'check_out', 'manual'])->default('check_in');
            $table->time('checkin_time')->nullable();
            $table->time('checkout_time')->nullable();
            $table->timestamp('recorded_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('username');
            $table->string('name');
            $table->string('role');
            $table->string('password_snapshot')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->after('email');
            }

            if (! Schema::hasColumn('users', 'role_id')) {
                $table->foreignId('role_id')->nullable()->after('username')->constrained('roles')->nullOnDelete();
            }
        });

        Schema::table('employees', function (Blueprint $table) {
            if (! Schema::hasColumn('employees', 'nik')) {
                $table->string('nik')->nullable()->unique()->after('user_id');
            }

            if (! Schema::hasColumn('employees', 'nip')) {
                $table->string('nip')->nullable()->after('nik');
            }

            if (! Schema::hasColumn('employees', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('date_of_birth');
            }

            if (! Schema::hasColumn('employees', 'jenis_kelamin')) {
                $table->tinyInteger('jenis_kelamin')->nullable()->after('gender');
            }

            if (! Schema::hasColumn('employees', 'telepon')) {
                $table->string('telepon')->nullable()->after('phone');
            }

            if (! Schema::hasColumn('employees', 'alamat')) {
                $table->text('alamat')->nullable()->after('address');
            }

            if (! Schema::hasColumn('employees', 'tanggal_mulai')) {
                $table->date('tanggal_mulai')->nullable()->after('hire_date');
            }

            if (! Schema::hasColumn('employees', 'order_date')) {
                $table->date('order_date')->nullable()->after('tanggal_mulai');
            }
        });

        Schema::table('attendance_records', function (Blueprint $table) {
            if (! Schema::hasColumn('attendance_records', 'status_id')) {
                $table->foreignId('status_id')->nullable()->after('employee_id')->constrained('attendance_statuses')->nullOnDelete();
            }

            if (! Schema::hasColumn('attendance_records', 'reason_id')) {
                $table->foreignId('reason_id')->nullable()->after('status_id')->constrained('attendance_reasons')->nullOnDelete();
            }

            if (! Schema::hasColumn('attendance_records', 'check_in_time_id')) {
                $table->foreignId('check_in_time_id')->nullable()->after('check_out_time')->constrained('presence_times')->nullOnDelete();
            }

            if (! Schema::hasColumn('attendance_records', 'check_out_time_id')) {
                $table->foreignId('check_out_time_id')->nullable()->after('check_in_time_id')->constrained('presence_times')->nullOnDelete();
            }
        });

        $now = Carbon::now();

        if (DB::table('roles')->count() === 0) {
            $roles = [
                ['slug' => 'super-admin', 'name' => 'Super Admin', 'description' => 'Akses penuh sistem'],
                ['slug' => 'admin', 'name' => 'Admin', 'description' => 'Pengelola sistem dan master data'],
                ['slug' => 'hr', 'name' => 'HR / Personalia', 'description' => 'Kelola kepegawaian & absensi'],
                ['slug' => 'employee', 'name' => 'Karyawan', 'description' => 'Pengguna umum'],
            ];

            foreach ($roles as $index => $role) {
                $roles[$index]['created_at'] = $now;
                $roles[$index]['updated_at'] = $now;
            }

            DB::table('roles')->insert($roles);
        }

        if (Schema::hasColumn('users', 'role')) {
            $roleMap = DB::table('roles')->pluck('id', 'slug');

            DB::table('users')->select(['id', 'role'])->orderBy('id')->chunk(100, function ($users) use ($roleMap) {
                foreach ($users as $user) {
                    $roleKey = $user->role ? Str::slug($user->role) : 'employee';
                    $resolvedRole = $roleMap[$roleKey] ?? $roleMap['employee'] ?? null;
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['role_id' => $resolvedRole]);
                }
            });

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        } elseif (Schema::hasColumn('users', 'role_id')) {
            $hasRolesAssigned = DB::table('users')->whereNotNull('role_id')->exists();

            if (! $hasRolesAssigned) {
                $defaultRoleId = DB::table('roles')->where('slug', 'employee')->value('id');
                if ($defaultRoleId) {
                    DB::table('users')->update(['role_id' => $defaultRoleId]);
                }
            }
        }

        $statusSeeds = [
            ['code' => 'present', 'label' => 'Hadir', 'description' => 'Karyawan hadir tepat waktu'],
            ['code' => 'late', 'label' => 'Terlambat', 'description' => 'Karyawan hadir namun terlambat', 'is_late' => true],
            ['code' => 'leave', 'label' => 'Izin', 'description' => 'Izin resmi', 'requires_reason' => true],
            ['code' => 'sick', 'label' => 'Sakit', 'description' => 'Tidak masuk karena sakit', 'requires_reason' => true],
            ['code' => 'absent', 'label' => 'Alpa', 'description' => 'Tidak ada keterangan', 'is_late' => false],
        ];

        if (DB::table('attendance_statuses')->count() === 0) {
            foreach ($statusSeeds as $index => $status) {
                $statusSeeds[$index]['is_late'] = $status['is_late'] ?? false;
                $statusSeeds[$index]['requires_reason'] = $status['requires_reason'] ?? false;
                $statusSeeds[$index]['created_at'] = $now;
                $statusSeeds[$index]['updated_at'] = $now;
            }

            DB::table('attendance_statuses')->insert($statusSeeds);
        }

        $reasonSeeds = [
            ['code' => 'dinas_diluar', 'label' => 'Dinas di luar kantor'],
            ['code' => 'sakit', 'label' => 'Sakit'],
            ['code' => 'alpa', 'label' => 'Tanpa keterangan'],
        ];

        if (DB::table('attendance_reasons')->count() === 0) {
            foreach ($reasonSeeds as $index => $reason) {
                $reasonSeeds[$index]['created_at'] = $now;
                $reasonSeeds[$index]['updated_at'] = $now;
            }

            DB::table('attendance_reasons')->insert($reasonSeeds);
        }

        $statusIdMap = DB::table('attendance_statuses')->pluck('id', 'code');
        $reasonIdMap = DB::table('attendance_reasons')->pluck('id', 'code');

        DB::table('employees')
            ->select(['id', 'employee_code', 'national_id', 'phone', 'address', 'date_of_birth', 'gender', 'hire_date'])
            ->orderBy('id')
            ->chunk(100, function ($employees) use ($now) {
                foreach ($employees as $employee) {
                    DB::table('employees')
                        ->where('id', $employee->id)
                        ->update([
                            'nik' => $employee->employee_code,
                            'nip' => $employee->national_id ?? $employee->employee_code,
                            'telepon' => $employee->phone,
                            'alamat' => $employee->address,
                            'tanggal_lahir' => $employee->date_of_birth,
                            'jenis_kelamin' => $employee->gender === 'male' ? 1 : ($employee->gender === 'female' ? 0 : null),
                            'tanggal_mulai' => $employee->hire_date,
                            'order_date' => $employee->hire_date ?? $now->toDateString(),
                        ]);
                }
            });

        DB::table('attendance_records')
            ->select(['id', 'employee_id', 'attendance_date', 'status', 'leave_reason', 'check_in_time', 'check_out_time'])
            ->orderBy('id')
            ->chunk(100, function ($records) use ($statusIdMap, $reasonIdMap, $now) {
                foreach ($records as $record) {
                    $checkInId = null;
                    $checkOutId = null;

                    if ($record->check_in_time) {
                        $checkInId = DB::table('presence_times')->insertGetId([
                            'employee_id' => $record->employee_id,
                            'type' => 'check_in',
                            'checkin_time' => $record->check_in_time,
                            'recorded_at' => $record->attendance_date && $record->check_in_time
                                ? Carbon::parse("{$record->attendance_date} {$record->check_in_time}")
                                : null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }

                    if ($record->check_out_time) {
                        $checkOutId = DB::table('presence_times')->insertGetId([
                            'employee_id' => $record->employee_id,
                            'type' => 'check_out',
                            'checkout_time' => $record->check_out_time,
                            'recorded_at' => $record->attendance_date && $record->check_out_time
                                ? Carbon::parse("{$record->attendance_date} {$record->check_out_time}")
                                : null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }

                    DB::table('attendance_records')
                        ->where('id', $record->id)
                        ->update([
                            'status_id' => $statusIdMap[$record->status] ?? null,
                            'reason_id' => $record->leave_reason
                                ? ($reasonIdMap[$record->leave_reason] ?? null)
                                : null,
                            'check_in_time_id' => $checkInId,
                            'check_out_time_id' => $checkOutId,
                        ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            if (Schema::hasColumn('attendance_records', 'check_out_time_id')) {
                $table->dropConstrainedForeignId('check_out_time_id');
            }

            if (Schema::hasColumn('attendance_records', 'check_in_time_id')) {
                $table->dropConstrainedForeignId('check_in_time_id');
            }

            if (Schema::hasColumn('attendance_records', 'reason_id')) {
                $table->dropConstrainedForeignId('reason_id');
            }

            if (Schema::hasColumn('attendance_records', 'status_id')) {
                $table->dropConstrainedForeignId('status_id');
            }
        });

        Schema::table('employees', function (Blueprint $table) {
            foreach (['order_date', 'tanggal_mulai', 'alamat', 'telepon', 'jenis_kelamin', 'tanggal_lahir', 'nip', 'nik'] as $column) {
                if (Schema::hasColumn('employees', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('employee')->after('username');
            }

            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropConstrainedForeignId('role_id');
            }
        });

        Schema::dropIfExists('photos');
        Schema::dropIfExists('presence_times');
        Schema::dropIfExists('attendance_reasons');
        Schema::dropIfExists('attendance_statuses');
        Schema::dropIfExists('roles');
    }
};
