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

public function forgotPassword(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email',
    ]);

    $this->authService->forgotPassword(
        $validated['email']
    );

    return response()->json([
        'message' => 'لینک بازیابی رمز عبور به ایمیل شما ارسال شد.',
    ]);
}

public function resetPassword(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email',
        'token' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $this->authService->resetPassword(
        $validated['email'],
        $validated['token'],
        $validated['password']
    );

    return response()->json([
        'message' => 'رمز عبور با موفقیت تغییر کرد.',
    ]);
}

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $result = $this->authService->login($validated);

        return response()->json([
            'message' => 'ورود با موفقیت انجام شد.',
            'user' => $result['user'],
            'token' => $result['token'],
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