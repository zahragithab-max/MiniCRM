<?php

namespace App\Modules\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $this->authService->register($validated);

        return response()->json([
            'message' => 'ثبت‌نام با موفقیت انجام شد.',
            'user' => $user,
        ], 201);
    }

    public function verifyEmail(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email',
        'code' => 'required|digits:6',
    ]);

    $user = $this->authService->verifyEmail(
        $validated['email'],
        $validated['code']
    );

    return response()->json([
        'message' => 'ایمیل شما با موفقیت تأیید شد.',
        'user' => $user,
    ]);
}

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = $this->authService->login($validated);

        return response()->json([
            'message' => 'ورود با موفقیت انجام شد.',
            'user' => $user,
        ]);
    }

    public function logout()
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'با موفقیت خارج شدید.',
        ]);
    }
}