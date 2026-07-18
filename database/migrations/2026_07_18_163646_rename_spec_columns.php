<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_specifications', function (Blueprint $table) {
            $table->renameColumn('attribute_name', 'label');
            $table->renameColumn('attribute_value', 'value');
        });
    }

    public function down(): void
    {
        Schema::table('product_specifications', function (Blueprint $table) {
            $table->renameColumn('label', 'attribute_name');
            $table->renameColumn('value', 'attribute_value');
        });
    }
};
