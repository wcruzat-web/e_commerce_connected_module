<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function ($table) {
            $table->dropColumn('tax');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function ($table) {
            $table->decimal('tax', 10, 2)->after('subtotal');
        });
    }
};
