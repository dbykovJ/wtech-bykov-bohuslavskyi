<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(private UserService $service) {}

    // API endpoints
    public function index()
    {
        return ApiResponse::success($this->service->getAllUsers());
    }

    public function store(Request $request)
    {
        return ApiResponse::created($this->service->createUser($request));
    }

    // Account page
    public function show()
    {
        return view('account.personal-data', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $this->service->updatePersonalData(Auth::user(), $request);

        return back()->with('success', 'Your details have been updated.');
    }
}
