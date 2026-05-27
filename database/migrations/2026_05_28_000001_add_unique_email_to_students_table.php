<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('students')) {
            return;
        }

        if (!Schema::hasColumn('students', 'email')) {
            return;
        }

        DB::table('students')
            ->whereNull('email')
            ->orWhere('email', '')
            ->update([
                'email' => DB::raw("CONCAT(nis, '@texmaco.sch.id')"),
            ]);

        Schema::table('students', function (Blueprint $table) {
            $table->unique('email', 'students_email_unique');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('students')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique('students_email_unique');
        });
    }
};
