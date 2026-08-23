<?php

namespace App\Modules\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): User
    {

        return User::create($data);
    }

    public function login(array $credentials): User
    {
        $user = User::where('email', $credentials['email'])->first();
    
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['اطلاعات ورود صحیح نیست.'],
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