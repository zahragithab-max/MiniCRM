<?php

namespace App\Modules\Quotes\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Quotes\Http\Requests\StoreQuoteRequest;
use App\Modules\Quotes\Models\Quote;
use App\Modules\Quotes\Services\QuoteService;
use App\Support\Helpers\JalaliHelper;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
    public function __construct(
        private QuoteService $quoteService
    ) {}

    public function index(): JsonResponse
    {
        $quotes = Quote::with([
            'deal',
            'account',
            'contact',
            'items.product',
        ])
            ->latest()
            ->paginate(15);

        $quotes->getCollection()->transform(
            fn (Quote $quote) => $this->formatDates($quote)
        );

        return $this->success($quotes);
    }

    public function store(StoreQuoteRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data = $this->quoteService->prepareVatSettings($data);

        $data['quote_number'] = $this->quoteService
            ->generateQuoteNumber();

        $data['status'] = $data['status'] ?? 'draft';

        $quote = Quote::create($data);

        $quote->load([
            'deal',
            'account',
            'contact',
            'items.product',
        ]);

        return $this->success(
            $this->formatDates($quote),
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        
        /** @var Quote $quote */
     $quote = Quote::with([
      'deal',
      'account',
      'contact',
      'items.product',
     ])->findOrFail($id);

        return $this->success(
            $this->formatDates($quote)
        );
    }

    private function formatDates(Quote $quote): array
    {
        $data = $quote->toArray();

        $data['issue_date'] = JalaliHelper::toJalali(
            $quote->issue_date
        );

        $data['valid_until'] = JalaliHelper::toJalali(
            $quote->valid_until
        );

        return $data;
    }
}
