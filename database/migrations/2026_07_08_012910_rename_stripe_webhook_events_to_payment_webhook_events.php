<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('stripe_webhook_events', 'payment_webhook_events');
    }

    public function down(): void
    {
        Schema::rename('payment_webhook_events', 'stripe_webhook_events');
    }
};
