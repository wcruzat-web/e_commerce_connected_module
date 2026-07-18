<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('customers', 'first_name')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn(['name', 'phone', 'address']);
                $table->string('first_name', 50)->after('customer_id');
                $table->string('last_name', 50)->after('first_name');
                $table->string('password', 255)->after('email');
                $table->string('phone_number', 20)->nullable()->after('password');
                $table->string('profile_picture', 255)->nullable()->after('phone_number');
                $table->enum('status', ['Active', 'Inactive'])->default('Inactive')->after('profile_picture');
                $table->timestamp('email_verified_at')->nullable()->after('status');
                $table->datetime('last_login')->nullable()->after('email_verified_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('customers', 'first_name')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn(['first_name', 'last_name', 'password', 'phone_number', 'profile_picture', 'status', 'email_verified_at', 'last_login']);
                $table->string('name')->after('customer_id');
                $table->string('phone', 20)->nullable()->after('email');
                $table->text('address')->nullable()->after('phone');
            });
        }
    }
};
