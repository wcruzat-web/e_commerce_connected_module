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
            $table->string('label', 100);
            $table->string('value', 100)->nullable();
            $table->timestamps();
        });

        if (!Schema::hasTable('product_compabilities')) {
            Schema::create('product_compabilities', function (Blueprint $table) {
                $table->id('compatibility_id');
                $table->foreignId('product_id')->constrained('products', 'id')->cascadeOnDelete();
                $table->string('category_name', 100);
                $table->string('item_name', 150);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        //
    }
};
