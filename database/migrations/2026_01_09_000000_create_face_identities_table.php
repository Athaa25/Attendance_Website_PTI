<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('face_identities', function (Blueprint $table) {
            $table->id();
            $table->string('person_slug')->unique();
            $table->string('name');
            $table->foreignId('pegawai_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('face_identities');
    }
};
