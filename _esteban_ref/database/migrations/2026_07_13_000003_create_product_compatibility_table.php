<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_compatibility', function (Blueprint $table) {
            $table->id('compatibility_id');
            $table->foreignId('product_id')->constrained('product_table', 'product_id')->cascadeOnDelete();
            $table->string('category_name', 100);
            $table->string('item_name', 150);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_compatibility');
    }
};
