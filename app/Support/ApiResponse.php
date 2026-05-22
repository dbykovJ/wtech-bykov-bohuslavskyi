<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(mixed $data, int $code = 200): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => $data], $code);
    }

    public static function created(mixed $data): JsonResponse
    {
        return self::success($data, 201);
    }

    public static function error(string $message, int $code = 400): JsonResponse
    {
        return response()->json(['status' => 'error', 'message' => $message], $code);
    }
}
