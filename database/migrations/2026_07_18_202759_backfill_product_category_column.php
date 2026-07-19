<?php

// [ESTEBAN] — backfill `category` column from `categories` table for existing products
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('UPDATE product_table
            JOIN categories ON product_table.category_id = categories.id
            SET product_table.category = categories.name
            WHERE product_table.category IS NULL AND product_table.category_id IS NOT NULL');
    }

    public function down(): void
    {
        // No reversal — data was null before, stays populated now
    }
};
