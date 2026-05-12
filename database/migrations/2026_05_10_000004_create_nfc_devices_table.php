<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nfc_devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('status')->default('offline');
            $table->dateTime('last_seen_at')->nullable();
            $table->dateTime('last_scan_at')->nullable();
            $table->unsignedInteger('scan_today')->default(0);
            $table->decimal('success_rate', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nfc_devices');
    }
};
