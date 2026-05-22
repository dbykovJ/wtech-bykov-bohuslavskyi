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
        Schema::create('item_color_size_counts', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('item_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('color_id')->constrained('colors')->onDelete('cascade');
            $table->enum('size', ['XS', 'S', 'M', 'L', 'XL', 'XXL']);
            $table->integer('count');
            $table->primary(['item_id', 'color_id', 'size']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_color_size_counts');
    }
};
