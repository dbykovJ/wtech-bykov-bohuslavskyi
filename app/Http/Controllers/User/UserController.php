<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $service){}

    public function index()
    {
        return ApiResponse::success($this->service->getAllUsers());
    }

    public function store(Request $request)
    {
        $user = $this->service->createUser($request->all());
        return ApiResponse::created($user);
    }
}
