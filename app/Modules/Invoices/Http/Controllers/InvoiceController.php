<?php

namespace App\Modules\Invoices\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Invoices\Http\Requests\StoreInvoiceRequest;
use App\Modules\Invoices\Models\Invoice;
use App\Modules\Invoices\Services\InvoiceService;
use App\Support\Helpers\JalaliHelper;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    public function __construct(
        private InvoiceService $invoiceService
    ) {}

    public function index(): JsonResponse
    {
        $invoices = Invoice::with([
            'deal',
            'account',
            'contact',
            'quote',
            'items.product',
        ])
            ->latest()
            ->paginate(15);

        $invoices->getCollection()->transform(
            fn (Invoice $invoice) => $this->formatDates($invoice)
        );

        return $this->success($invoices);
    }

    public function store(StoreInvoiceRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data = $this->invoiceService->prepareVatSettings($data);

        $data['invoice_number'] = $this->invoiceService
            ->generateInvoiceNumber();

        $invoice = Invoice::create($data);

        $invoice->load([
            'deal',
            'account',
            'contact',
            'quote',
            'items.product',
        ]);

        return $this->success(
            $this->formatDates($invoice),
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::with([
            'deal',
            'account',
            'contact',
            'quote',
            'items.product',
        ])->findOrFail($id);

        return $this->success(
            $this->formatDates($invoice)
        );
    }

    public function issue(int $id): JsonResponse
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::findOrFail($id);

        $invoice = $this->invoiceService->issue($invoice);

        $invoice->load([
            'deal',
            'account',
            'contact',
            'quote',
            'items.product',
        ]);

        return $this->success(
            $this->formatDates($invoice)
        );
    }

    private function formatDates(Invoice $invoice): array
    {
        $data = $invoice->toArray();

        $data['issue_date'] = JalaliHelper::toJalali(
            $invoice->issue_date
        );

        $data['due_date'] = JalaliHelper::toJalali(
            $invoice->due_date
        );

        return $data;
    }
}