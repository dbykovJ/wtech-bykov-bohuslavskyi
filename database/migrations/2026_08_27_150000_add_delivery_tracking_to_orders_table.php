<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_carrier', 80)->nullable()->after('shipping_method');
            $table->string('tracking_number', 120)->nullable()->index()->after('delivery_carrier');
            $table->timestamp('shipped_at')->nullable()->after('paid_at');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['tracking_number']);
            $table->dropColumn(['delivery_carrier', 'tracking_number', 'shipped_at', 'delivered_at']);
        });
    }
};
