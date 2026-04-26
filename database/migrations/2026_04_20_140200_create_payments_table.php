<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('provider', 32)->default('stripe');
            $table->string('provider_payment_id')->nullable()->index();
            $table->string('status', 32);
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('usd');
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->unique(['order_id', 'provider']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
