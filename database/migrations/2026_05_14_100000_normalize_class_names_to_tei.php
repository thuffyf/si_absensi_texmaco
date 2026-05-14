<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Samakan penamaan kelas ke X TEI, XI TEI, XII TEI berdasarkan tingkat (angka Romawi di awal).
     * Urutan penting: XII dulu, lalu XI, lalu X — agar "XII" tidak tertangkap sebagai "XI".
     */
    public function up(): void
    {
        foreach (['students', 'schedules'] as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'class_name')) {
                continue;
            }

            DB::table($table)->where('class_name', 'like', 'XII%')->update(['class_name' => 'XII TEI']);

            DB::table($table)
                ->where('class_name', 'like', 'XI%')
                ->where('class_name', 'not like', 'XII%')
                ->update(['class_name' => 'XI TEI']);

            DB::table($table)
                ->where('class_name', 'like', 'X%')
                ->where('class_name', 'not like', 'XI%')
                ->where('class_name', 'not like', 'XII%')
                ->update(['class_name' => 'X TEI']);
        }
    }

    /**
     * Tidak dibalik: data asli (IPA/IPS/… ) tidak tersimpan.
     */
    public function down(): void
    {
        //
    }
};
