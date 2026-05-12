<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->string('name');
            $table->string('username')->nullable()->unique();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('class_name');
            $table->string('major')->nullable();
            $table->string('status')->default('aktif');
            $table->string('nfc_type')->default('belum_terdaftar');
            $table->string('uid_kartu')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
