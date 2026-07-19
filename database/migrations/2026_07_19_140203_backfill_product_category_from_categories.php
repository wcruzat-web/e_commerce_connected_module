<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('UPDATE products p JOIN categories c ON p.category_id = c.id SET p.category = c.name WHERE p.category IS NULL OR p.category = \'\'');
    }

    public function down(): void
    {
    }
};
