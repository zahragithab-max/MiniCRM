<?php

namespace App\Modules\Settings\Currency\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Currency\Requests\UpdateCurrencySettingRequest;
use App\Modules\Settings\Currency\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use App\Modules\Settings\Currency\Requests\ConvertCurrencyRequest;

class CurrencySettingController extends Controller
{
    public function __construct(
        private CurrencyService $currencyService
    ) {
    }

    public function show(): JsonResponse
    {
        return $this->success(
            $this->currencyService->get()
        );
    }

    public function update(
        UpdateCurrencySettingRequest $request
    ): JsonResponse {
        return $this->success(
            $this->currencyService->update(
                $request->validated('currency'),
                $request->validated('usd_rate')
            )
        );
    }


public function convert(
    ConvertCurrencyRequest $request
): JsonResponse {
    $amount = (float) $request->validated('amount');
    $from = $request->validated('from');
    $to = $request->validated('to');

    if ($from === $to) {
        $convertedAmount = $amount;
    } else {
        $tomanAmount = $this->currencyService->convertToToman(
            $amount,
            $from
        );

        $convertedAmount = $this->currencyService->convertFromToman(
            $tomanAmount,
            $to
        );
    }

    return $this->success([
        'amount' => $amount,
        'from' => $from,
        'to' => $to,
        'converted_amount' => $convertedAmount,
    ]);
}
}