<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            ColorSeeder::class,
            ProductSeeder::class,
            ProductImageSeeder::class,
            SaleSeeder::class,
        ]);

        // AdminSeeder / UserWithCartSeeder create accounts with a known
        // default password — never run them against a production database.
        if (! app()->environment('production')) {
            $this->call([
                AdminSeeder::class,
                UserWithCartSeeder::class,
            ]);
        }
    }
}
