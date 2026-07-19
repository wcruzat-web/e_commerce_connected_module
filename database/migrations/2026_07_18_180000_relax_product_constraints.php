<?php

// [ESTEBAN] — make slug/category_id nullable for Esteban's Product schema compatibility
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_table', function (Blueprint $table) {
            $table->string('slug')->nullable()->change();
            $table->unsignedBigInteger('category_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('product_table', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
        });
    }
};
