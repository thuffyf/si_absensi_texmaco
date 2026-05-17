<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update student class_name and major to match the correct format
        // Extract class (X, XI, XII) from existing class_name values
        // Set major to TEI for all students
        
        DB::statement("
            UPDATE students 
            SET 
                class_name = CASE 
                    WHEN class_name LIKE 'X%' THEN 'X'
                    WHEN class_name LIKE 'XI%' THEN 'XI'
                    WHEN class_name LIKE 'XII%' THEN 'XII'
                    WHEN class_name LIKE '10%' THEN 'X'
                    WHEN class_name LIKE '11%' THEN 'XI'
                    WHEN class_name LIKE '12%' THEN 'XII'
                    ELSE class_name
                END,
                major = 'TEI'
            WHERE major IS NULL OR major = '' OR major != 'TEI'
        ");
        
        // Also update schedules class_name to match
        DB::statement("
            UPDATE schedules 
            SET 
                class_name = CASE 
                    WHEN class_name LIKE 'X%' THEN 'X'
                    WHEN class_name LIKE 'XI%' THEN 'XI'
                    WHEN class_name LIKE 'XII%' THEN 'XII'
                    WHEN class_name LIKE '10%' THEN 'X'
                    WHEN class_name LIKE '11%' THEN 'XI'
                    WHEN class_name LIKE '12%' THEN 'XII'
                    ELSE class_name
                END
        ");
    }

    public function down(): void
    {
        // Revert is not straightforward since we don't know the original values
        // This migration is meant to correct data format
    }
};
