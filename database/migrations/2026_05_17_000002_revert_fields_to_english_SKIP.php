<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * This migration is skipped because the database schema
     * already uses English column names from initial creation.
     * Renaming migrations were already applied previously.
     */
    public function up(): void
    {
        // No-op: schema already in English
    }

    public function down(): void
    {
        // No-op
    }
};
