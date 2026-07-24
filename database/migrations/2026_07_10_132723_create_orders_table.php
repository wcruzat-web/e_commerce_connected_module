<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('order_number')->unique();
            $table->string('status')->default('pending');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);
            $table->string('shipping_name');
            $table->string('shipping_email');
            $table->string('shipping_phone', 20)->nullable();
            $table->text('shipping_address');
            $table->text('notes')->nullable();
            $table->boolean('customer_received')->default(false);
            $table->string('payment_status')->default('pending');
            $table->string('finance_transaction_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('coupon_code', 50)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')
                  ->references('customer_id')
                  ->on('customers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
