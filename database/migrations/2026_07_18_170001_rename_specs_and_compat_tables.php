<?php

// [ESTEBAN] — rename product_specifications → product_specification, product_compabilities → product_compatibilities
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('product_specifications', 'product_specification');
        Schema::rename('product_compabilities', 'product_compatibilities');
    }

    public function down(): void
    {
        Schema::rename('product_specification', 'product_specifications');
        Schema::rename('product_compatibilities', 'product_compabilities');
    }
};
