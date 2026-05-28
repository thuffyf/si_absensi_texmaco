<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update attendance status values 'alpha' or 'alfa' -> 'alpa'.
     */
    public function up(): void
    {
        DB::table('attendances')->where('status', 'alpha')->update(['status' => 'alpa']);
        DB::table('attendances')->where('status', 'alfa')->update(['status' => 'alpa']);
    }

    /**
     * Reverse the migrations.
     * Revert 'alpa' back to 'alpha'.
     */
    public function down(): void
    {
        DB::table('attendances')->where('status', 'alpa')->update(['status' => 'alpha']);
    }
};
