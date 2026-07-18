<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('product_specifications');

        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id('spec_id');
            $table->foreignId('product_id')->constrained('products', 'id')->cascadeOnDelete();
            $table->string('category_name', 100);
            $table->string('attribute_name', 100);
            $table->string('attribute_value', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_specifications');
    }
};
