<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scan_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('uid_kartu', 100);
            $table->foreignId('device_id')->nullable()->constrained('nfc_devices')->nullOnDelete();
            $table->enum('status', ['success', 'already_attended', 'unregistered', 'error'])->default('error');
            $table->string('response_message', 255)->nullable();
            $table->timestamp('scanned_at')->useCurrent();
            $table->timestamps();
            
            $table->index('uid_kartu');
            $table->index('scanned_at');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scan_attempts');
    }
};
