<?php

namespace App\Modules\Invoices\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Invoices\Http\Requests\AddInvoiceItemRequest;
use App\Modules\Invoices\Models\Invoice;
use App\Modules\Invoices\Services\InvoiceItemService;
use Illuminate\Http\JsonResponse;
use App\Modules\Invoices\Http\Requests\UpdateInvoiceItemRequest;

class InvoiceItemController extends Controller
{
    public function __construct(
        private InvoiceItemService $invoiceItemService
    ) {}

    public function index(int $invoiceId): JsonResponse
    {
        $invoice = Invoice::with('items.product')
            ->findOrFail($invoiceId);

        return $this->success($invoice->items);
    }

    public function store(
        AddInvoiceItemRequest $request,
        int $invoiceId
    ): JsonResponse {
        $invoice = Invoice::findOrFail($invoiceId);

        $invoiceItem = $this->invoiceItemService->addItem(
            $invoice,
            $request->validated()
        );

        return $this->success($invoiceItem, 201);
    }
    public function update(
        UpdateInvoiceItemRequest $request,
        int $invoiceId,
        int $itemId
    ): JsonResponse {
        $invoice = Invoice::findOrFail($invoiceId);
    
        $invoiceItem = $invoice->items()
            ->whereKey($itemId)
            ->firstOrFail();
    
        $invoiceItem = $this->invoiceItemService->updateItem(
            $invoiceItem,
            $request->validated()
        );
    
        return $this->success($invoiceItem);
    }
}