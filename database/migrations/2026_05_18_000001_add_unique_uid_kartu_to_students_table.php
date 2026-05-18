<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('students', 'uid_kartu')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            $table->unique('uid_kartu');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('students', 'uid_kartu')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique(['uid_kartu']);
        });
    }
};
