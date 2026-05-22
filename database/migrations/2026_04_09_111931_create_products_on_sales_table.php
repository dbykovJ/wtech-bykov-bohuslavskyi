<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products_on_sales', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->foreignId('sale_id')->constrained()->onDelete('restrict');
            $table->primary(['product_id', 'sale_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_on_sales');
    }
};
