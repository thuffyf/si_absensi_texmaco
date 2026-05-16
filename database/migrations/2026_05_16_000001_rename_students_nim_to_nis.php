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

        if (Schema::hasColumn('students', 'nis')) {
            return;
        }

        if (!Schema::hasColumn('students', 'nim')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE students CHANGE nim nis VARCHAR(255)');
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('nim', 'nis');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('students')) {
            return;
        }

        if (Schema::hasColumn('students', 'nim')) {
            return;
        }

        if (!Schema::hasColumn('students', 'nis')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE students CHANGE nis nim VARCHAR(255)');
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('nis', 'nim');
        });
    }
};
