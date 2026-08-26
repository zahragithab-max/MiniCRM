<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'احراز هویت الزامی است.',
            ], 401);
        }

        $hasPermission = $user->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();

        if (!$hasPermission) {
            return response()->json([
                'message' => 'شما دسترسی لازم برای انجام این عملیات را ندارید.',
            ], 403);
        }

        return $next($request);
    }
}
