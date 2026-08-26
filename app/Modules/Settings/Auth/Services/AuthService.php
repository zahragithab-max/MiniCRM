<?php

namespace App\Modules\Settings\Auth\Services;

use App\Jobs\SendVerificationCodeJob;
use App\Mail\PasswordResetMail;
use App\Modules\Settings\Auth\Models\User;
use App\Modules\Settings\Auth\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

    public function forgotPassword(string $email): void
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['کاربری با این ایمیل پیدا نشد.'],
            ]);
        }

        $token = bin2hex(random_bytes(32));

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        Mail::to($email)->send(
            new PasswordResetMail(
                $email,
                $token
            )
        );
    }

    public function resetPassword(
        string $email,
        string $token,
        string $password
    ): void {
        $reset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$reset) {
            throw ValidationException::withMessages([
                'token' => ['توکن بازیابی معتبر نیست.'],
            ]);
        }

        if (!Hash::check($token, $reset->token)) {
            throw ValidationException::withMessages([
                'token' => ['توکن بازیابی اشتباه است.'],
            ]);
        }
        if (Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            throw ValidationException::withMessages([
                'token' => ['توکن بازیابی منقضی شده است.'],
            ]);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['کاربری با این ایمیل پیدا نشد.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($password),
        ]);

        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();
    }

    public function login(array $credentials): array
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

        $token = $user->createToken('MiniCRM')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(): void
    {
        $user = auth()->user();

        if ($user) {
            $user->currentAccessToken()?->delete();
        }
    }
}
