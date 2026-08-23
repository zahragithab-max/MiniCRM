<?php

namespace App\Modules\Auth;

use App\Jobs\SendVerificationCodeJob;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): User
    {
        $user = User::create($data);

        $plainCode = (string) random_int(100000, 999999);

        $verificationCode = VerificationCode::create([
            'user_id' => $user->id,
            'type' => 'email_verification',
            'code' => Hash::make($plainCode),
            'expires_at' => now()->addMinutes(10),
            'used_at' => null,
            'attempts' => 0,
        ]);

        SendVerificationCodeJob::dispatch(
            $verificationCode,
            $plainCode
        );

        return $user;
    }

    public function verifyEmail(string $email, string $plainCode): User
{
    $user = User::where('email', $email)->first();

    if (!$user) {
        throw ValidationException::withMessages([
            'email' => ['کاربری با این ایمیل پیدا نشد.'],
        ]);
    }

    if ($user->email_verified_at) {
        throw ValidationException::withMessages([
            'code' => ['ایمیل شما قبلاً تأیید شده است.'],
        ]);
    }

    $verificationCode = VerificationCode::where('user_id', $user->id)
        ->where('type', 'email_verification')
        ->whereNull('used_at')
        ->latest()
        ->first();

    if (!$verificationCode) {
        throw ValidationException::withMessages([
            'code' => ['کد تأیید معتبر نیست.'],
        ]);
    }

    if ($verificationCode->expires_at->isPast()) {
        throw ValidationException::withMessages([
            'code' => ['کد تأیید منقضی شده است.'],
        ]);
    }

    if ($verificationCode->attempts >= 5) {
        throw ValidationException::withMessages([
            'code' => ['تعداد تلاش‌های مجاز برای این کد تمام شده است.'],
        ]);
    }

    if (!Hash::check($plainCode, $verificationCode->code)) {
        $verificationCode->increment('attempts');

        throw ValidationException::withMessages([
            'code' => ['کد تأیید اشتباه است.'],
        ]);
    }

    $verificationCode->update([
        'used_at' => now(),
    ]);

    $user->update([
        'email_verified_at' => now(),
    ]);

    return $user;
}

    public function login(array $credentials): User
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['اطلاعات ورود صحیح نیست.'],
            ]);
        }

        if (!$user->email_verified_at) {
            throw ValidationException::withMessages([
                'email' => ['ایمیل شما هنوز تأیید نشده است.'],
            ]);
        }

        auth()->login($user);

        return $user;
    }

    public function logout(): void
    {
        auth()->logout();
    }
}