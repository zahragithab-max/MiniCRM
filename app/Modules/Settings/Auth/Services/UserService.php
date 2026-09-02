<?php

namespace App\Modules\Settings\Auth\Services;

use App\Modules\Settings\Auth\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAll()
    {
        return User::with('roles')->get();
    }

    public function findById(int $id)
    {
        return User::with('roles')->findOrFail($id);
    }

    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return $user->load('roles');
    }

    public function deactivate(User $user)
    {
        $user->update([
            'email_verified_at' => null,
        ]);

        return $user;
    }
}