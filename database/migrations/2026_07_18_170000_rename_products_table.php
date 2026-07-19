<?php

// [ESTEBAN] — rename products → product_table, columns, add is_featured + category
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_images', fn (Blueprint $t) => $t->dropForeign(['product_id']));
        Schema::table('product_specifications', fn (Blueprint $t) => $t->dropForeign(['product_id']));
        Schema::table('product_compabilities', fn (Blueprint $t) => $t->dropForeign(['product_id']));
        Schema::table('product_reviews', fn (Blueprint $t) => $t->dropForeign(['product_id']));
        Schema::table('cart_items', fn (Blueprint $t) => $t->dropForeign(['product_id']));
        Schema::table('order_items', fn (Blueprint $t) => $t->dropForeign(['product_id']));
        Schema::table('products', fn (Blueprint $t) => $t->dropForeign(['category_id']));

        Schema::rename('products', 'product_table');

        Schema::table('product_table', function (Blueprint $table) {
            $table->renameColumn('id', 'product_id');
            $table->renameColumn('name', 'product_name');
            $table->renameColumn('featured_image', 'product_image');
            $table->boolean('is_featured')->default(false);
            $table->string('category', 100)->nullable()->after('category_id');
        });

        Schema::table('product_images', fn (Blueprint $t) => $t->foreign('product_id')->references('product_id')->on('product_table')->cascadeOnDelete());
        Schema::table('product_specifications', fn (Blueprint $t) => $t->foreign('product_id')->references('product_id')->on('product_table')->cascadeOnDelete());
        Schema::table('product_compabilities', fn (Blueprint $t) => $t->foreign('product_id')->references('product_id')->on('product_table')->cascadeOnDelete());
        Schema::table('product_reviews', fn (Blueprint $t) => $t->foreign('product_id')->references('product_id')->on('product_table')->cascadeOnDelete());
        Schema::table('cart_items', fn (Blueprint $t) => $t->foreign('product_id')->references('product_id')->on('product_table')->cascadeOnDelete());
        Schema::table('order_items', fn (Blueprint $t) => $t->foreign('product_id')->references('product_id')->on('product_table')->cascadeOnDelete());
        Schema::table('product_table', fn (Blueprint $t) => $t->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete());
    }

    public function down(): void
    {
        Schema::table('product_images', fn (Blueprint $t) => $t->dropForeign(['product_id']));
        Schema::table('product_specifications', fn (Blueprint $t) => $t->dropForeign(['product_id']));
        Schema::table('product_compabilities', fn (Blueprint $t) => $t->dropForeign(['product_id']));
        Schema::table('product_reviews', fn (Blueprint $t) => $t->dropForeign(['product_id']));
        Schema::table('cart_items', fn (Blueprint $t) => $t->dropForeign(['product_id']));
        Schema::table('order_items', fn (Blueprint $t) => $t->dropForeign(['product_id']));
        Schema::table('product_table', fn (Blueprint $t) => $t->dropForeign(['category_id']));

        Schema::table('product_table', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'category']);
            $table->renameColumn('product_id', 'id');
            $table->renameColumn('product_name', 'name');
            $table->renameColumn('product_image', 'featured_image');
        });

        Schema::rename('product_table', 'products');

        Schema::table('product_images', fn (Blueprint $t) => $t->foreign('product_id')->references('id')->on('products')->cascadeOnDelete());
        Schema::table('product_specifications', fn (Blueprint $t) => $t->foreign('product_id')->references('id')->on('products')->cascadeOnDelete());
        Schema::table('product_compabilities', fn (Blueprint $t) => $t->foreign('product_id')->references('id')->on('products')->cascadeOnDelete());
        Schema::table('product_reviews', fn (Blueprint $t) => $t->foreign('product_id')->references('id')->on('products')->cascadeOnDelete());
        Schema::table('cart_items', fn (Blueprint $t) => $t->foreign('product_id')->references('id')->on('products')->cascadeOnDelete());
        Schema::table('order_items', fn (Blueprint $t) => $t->foreign('product_id')->references('id')->on('products')->cascadeOnDelete());
        Schema::table('products', fn (Blueprint $t) => $t->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete());
    }
};
