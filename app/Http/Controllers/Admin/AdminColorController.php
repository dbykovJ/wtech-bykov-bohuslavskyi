<?php

namespace App\Http\Controllers\Cart\PromoCode\Admin;

use App\Http\Controllers\Cart\PromoCode\Controller;
use App\Models\Color;
use App\Services\Color\ColorService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class AdminColorController extends Controller
{

    public function __construct(private ColorService $service){ }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ApiResponse::success($this->service->getColors());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $color = $this->service->createColor($request->all());
        return ApiResponse::created($color);

    }

    /**
     * Display the specified resource.
     */
    public function show(Color $color)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Color $color)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Color $color)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
    {
        //
    }
}
