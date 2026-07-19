<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_table', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('product_name', 150);
            $table->string('sku', 100)->unique();
            $table->string('brand', 100);
            $table->string('category', 100);
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('product_image', 255)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_table');
    }
};
