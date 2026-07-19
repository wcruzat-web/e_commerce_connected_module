<?php

// [ESTEBAN] — original migration, unchanged
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revenue_overview', function (Blueprint $table) {
            $table->id('revenue_id');
            $table->string('month_label', 20);
            $table->year('overview_year');
            $table->decimal('revenue_amount', 12, 2);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revenue_overview');
    }
};
