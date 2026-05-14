<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homerooms', function (Blueprint $table) {
            $table->id();
            $table->string('class_name', 100)->unique();
            $table->string('homeroom_teacher_name', 200);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homerooms');
    }
};
