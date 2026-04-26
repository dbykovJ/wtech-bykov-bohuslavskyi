<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status', 32)->default('pending_payment');
            $table->string('currency', 3)->default('usd');
            $table->string('promo_code', 64)->nullable();
            $table->decimal('subtotal_before_discount', 10, 2);
            $table->decimal('sales_discount_total', 10, 2)->default(0);
            $table->decimal('promo_discount_total', 10, 2)->default(0);
            $table->decimal('discount_total', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('shipping_full_name', 120);
            $table->string('shipping_email', 120);
            $table->string('shipping_phone', 40);
            $table->string('shipping_street', 150);
            $table->string('shipping_city', 100);
            $table->string('shipping_postal_code', 30);
            $table->string('shipping_country', 2);
            $table->string('stripe_checkout_session_id')->nullable()->index();
            $table->string('stripe_payment_intent_id')->nullable()->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

