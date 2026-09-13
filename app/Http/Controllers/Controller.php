<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function success(
        mixed $data = null,
        int $status = 200
    ): JsonResponse {
        return response()->json($data, $status);
    }
}
