<?php

namespace App\Services\Color;
use App\Models\Color;

class ColorService
{
    public function getColors()
    {
        return Color::all();
    }

    public function createColor($request){

        $validated = $request->validate([
            'name' => 'required|string|max:32',
            'hex_code' => 'required|string|max:6|min:6|unique:colors,hex_code|regex:/^[0-9a-fA-F]{6}$/',
        ]);

        return Color::create([
            'name' => $validated->name,
            'hex_code' => $validated->hex_code,
        ]);
    }

}
