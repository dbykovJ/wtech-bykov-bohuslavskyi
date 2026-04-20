<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'image_url')) {
            return;
        }

        DB::table('products')
            ->select('id', 'image_url')
            ->whereNotNull('image_url')
            ->orderBy('id')
            ->chunkById(200, function ($products) {
                $now = now();
                $rows = [];

                foreach ($products as $product) {
                    $rows[] = [
                        'product_id' => $product->id,
                        'image_url' => $product->image_url,
                        'size' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if ($rows !== []) {
                    DB::table('product_images')->insert($rows);
                }
            });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'image_url')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->string('image_url')->nullable();
        });

        DB::table('products')
            ->select('id')
            ->orderBy('id')
            ->chunkById(200, function ($products) {
                foreach ($products as $product) {
                    $firstImagePath = DB::table('product_images')
                        ->where('product_id', $product->id)
                        ->orderBy('id')
                        ->value('image_url');

                    DB::table('products')
                        ->where('id', $product->id)
                        ->update(['image_url' => $firstImagePath]);
                }
            });
    }
};

