<?php
// [CRUZAT] Original migration (address_type, street, barangay, city, etc.)
// [AGNER]  Added recipient_name, phone_number, softDeletes (columns needed by CustomerAddress model)

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->bigIncrements('address_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('address_type', 20);
            // [AGNER] recipient_name + phone_number added
            $table->string('recipient_name', 100);
            $table->string('phone_number', 20);
            $table->string('street', 150);
            $table->string('barangay', 100);
            $table->string('city', 100);
            $table->string('province', 100);
            $table->string('postal_code', 10);
            $table->string('country', 100);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            // [AGNER] Added softDeletes
            $table->softDeletes();

            $table->foreign('customer_id')
                  ->references('customer_id')
                  ->on('customers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
