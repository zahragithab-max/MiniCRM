<?php

namespace App\Modules\Invoices\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Invoices\Http\Requests\StoreInvoiceRequest;
use App\Modules\Invoices\Models\Invoice;
use App\Modules\Invoices\Services\InvoiceService;
use App\Support\Helpers\JalaliHelper;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Modules\Invoices\Http\Requests\ChangeInvoiceCurrencyRequest;

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


    public function changeCurrency(
        ChangeInvoiceCurrencyRequest $request,
        int $id
    ): JsonResponse {
        /** @var Invoice $invoice */
        $invoice = Invoice::findOrFail($id);
    
        $invoice = $this->invoiceService->changeCurrency(
            $invoice,
            $request->validated('currency')
        );
    
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

    public function pdf(int $id)
    {
        $invoice = Invoice::with([
            'deal',
            'account',
            'contact',
            'quote',
            'items.product',
        ])->findOrFail($id);
    
        $html = '
            <html>
            <head>
                <meta charset="UTF-8">
                <style>
                    body {
                        font-family: DejaVu Sans, sans-serif;
                        direction: ltr;
                        font-size: 12px;
                    }
    
                    h1 {
                        text-align: center;
                    }
    
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }
    
                    th, td {
                        border: 1px solid #000;
                        padding: 8px;
                        text-align: left;
                    }
    
                    .total {
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <h1>Invoice</h1>
    
                <p>Invoice Number: ' . htmlspecialchars($invoice->invoice_number) . '</p>
                <p>Issue Date: ' . htmlspecialchars($invoice->issue_date?->format('Y-m-d')) . '</p>
                <p>Due Date: ' . htmlspecialchars($invoice->due_date?->format('Y-m-d')) . '</p>
                <p>Account: ' . htmlspecialchars($invoice->account?->name ?? '') . '</p>
                <p>Contact: ' . htmlspecialchars($invoice->contact?->name ?? '') . '</p>
    
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Discount</th>
                            <th>Tax</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>';
    
        foreach ($invoice->items as $item) {
            $html .= '
                        <tr>
                            <td>' . htmlspecialchars($item->product?->name ?? '') . '</td>
                            <td>' . $item->quantity . '</td>
                            <td>' . $item->unit_price . '</td>
                            <td>' . $item->discount . '</td>
                            <td>' . $item->tax . '</td>
                            <td>' . $item->total . '</td>
                        </tr>';
        }
    
        $html .= '
                    </tbody>
                </table>
    
                <div class="total">
                    <p>Subtotal: ' . $invoice->subtotal . '</p>
                    <p>VAT: ' . $invoice->vat . '</p>
                    <p>Grand Total: ' . $invoice->grand_total . '</p>
                </div>
            </body>
            </html>';
    
        $pdf = Pdf::loadHTML($html);
    
        return $pdf->download(
            $invoice->invoice_number . '.pdf'
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