<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products_on_sales', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['sale_id']);
        });

        Schema::table('products_on_sales', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('sale_id')->references('id')->on('sales')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products_on_sales', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['sale_id']);
        });

        Schema::table('products_on_sales', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->restrictOnDelete();
            $table->foreign('sale_id')->references('id')->on('sales')->restrictOnDelete();
        });
    }
};
