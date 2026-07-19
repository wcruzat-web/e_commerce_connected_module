<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_specifications', function (Blueprint $table) {
            $table->boolean('is_highlight')->default(false)->after('value');
            $table->unsignedInteger('sort_order')->default(0)->after('is_highlight');
        });
    }

    public function down(): void
    {
        Schema::table('product_specifications', function (Blueprint $table) {
            $table->dropColumn(['is_highlight', 'sort_order']);
        });
    }
};