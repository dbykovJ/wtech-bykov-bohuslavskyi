<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->foreignId('color_id')->constrained('colors')->onDelete('restrict');
            $table->enum('size', ['XS', 'S', 'M', 'L', 'XL', 'XXL']);
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price_before_discount', 10, 2);
            $table->decimal('unit_sales_discount_percent', 5, 2)->default(0);
            $table->decimal('unit_promo_discount_percent', 5, 2)->default(0);
            $table->decimal('unit_discount_percent', 5, 2)->default(0);
            $table->decimal('unit_price_after_discount', 10, 2);
            $table->decimal('line_total', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

