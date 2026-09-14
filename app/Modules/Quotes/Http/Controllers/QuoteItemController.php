<?php

namespace App\Modules\Quotes\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Quotes\Http\Requests\AddQuoteItemRequest;
use App\Modules\Quotes\Models\Quote;
use App\Modules\Quotes\Services\QuoteItemService;
use Illuminate\Http\JsonResponse;

class QuoteItemController extends Controller
{
    public function __construct(
        private QuoteItemService $quoteItemService
    ) {}

    public function index(int $quoteId): JsonResponse
    {
        $quote = Quote::with('items.product')
            ->findOrFail($quoteId);

        return $this->success($quote->items);
    }

    public function store(
        AddQuoteItemRequest $request,
        int $quoteId
    ): JsonResponse {
        $quote = Quote::findOrFail($quoteId);

        $quoteItem = $this->quoteItemService->addItem(
            $quote,
            $request->validated()
        );

        return $this->success($quoteItem, 201);
    }
}