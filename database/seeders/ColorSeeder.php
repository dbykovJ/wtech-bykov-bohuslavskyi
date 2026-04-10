<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Black',  'hex_code' => '#000000'],
            ['name' => 'White',  'hex_code' => '#FFFFFF'],
            ['name' => 'Red',    'hex_code' => '#FF0000'],
            ['name' => 'Navy',   'hex_code' => '#001F5B'],
            ['name' => 'Green',  'hex_code' => '#2D6A4F'],
        ];

        foreach ($colors as $color) {
            Color::firstOrCreate(['name' => $color['name']], $color);
        }
    }
}
