<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('device_id')->nullable()->constrained('nfc_devices')->nullOnDelete();
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->nullOnDelete();
            $table->date('attendance_date');
            $table->time('attendance_time')->nullable();
            $table->string('status');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
