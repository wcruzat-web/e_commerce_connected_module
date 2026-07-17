<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_tracking', function (Blueprint $table) {
            $table->bigInteger('tracking_id')->primary()->autoIncrement();
            $table->unsignedBigInteger('order_id');
            $table->string('tracking_number', 50)->unique();
            $table->string('order_status');
            $table->string('courier_name', 100)->default('J&T Express');
            $table->string('shipped_from', 150);
            $table->date('estimated_delivery_date');
            $table->dateTime('last_updated');
            $table->string('sync_status');
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('orders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_tracking');
    }
};
