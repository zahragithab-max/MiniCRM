<?php

namespace App\Modules\Dashboard\Services;

use App\Modules\Deals\Models\Deal;
use App\Modules\Invoices\Models\Invoice;
use App\Modules\Leads\Models\Lead;
use App\Modules\Tickets\Models\Ticket;
use Illuminate\Support\Carbon;

class DashboardService
{
    public function getDashboard(): array
    {
        $now = Carbon::now();

        return [
            'stats' => [
                'total_leads' => Lead::count(),

                'open_deals' => Deal::where(
                    'status',
                    'open'
                )->count(),

                'monthly_revenue' => Invoice::where(
                    'status',
                    'paid'
                )
                    ->whereBetween('issue_date', [
                        $now->copy()->startOfMonth(),
                        $now->copy()->endOfMonth(),
                    ])
                    ->sum('grand_total'),

                'open_tickets' => Ticket::whereIn(
                    'status',
                    ['open', 'pending']
                )->count(),

                'monthly_sales' => Deal::whereBetween(
                    'created_at',
                    [
                        $now->copy()->startOfMonth(),
                        $now->copy()->endOfMonth(),
                    ]
                )->sum('amount'),
            ],

            'sales_performance' => Deal::query()
                ->with('owner')
                ->selectRaw(
                    'owner_id, COUNT(*) as deals_count, SUM(amount) as sales'
                )
                ->groupBy('owner_id')
                ->get()
                ->map(function ($deal) {
                    return [
                        'user_id' => $deal->owner_id,
                        'name' => $deal->owner?->name,
                        'deals_count' => $deal->deals_count,
                        'sales' => $deal->sales,
                    ];
                })
                ->values(),

            'charts' => [
                'monthly_sales' => [
                    'type' => 'line',
                    'data' => $this->getMonthlySalesChart(),
                ],
            ],

            'widgets' => $this->getWidgetDefinitions(),
        ];
    }

    private function getMonthlySalesChart(): array
    {
        $start = Carbon::now()
            ->startOfMonth()
            ->subMonths(11);

        $end = Carbon::now()->endOfMonth();

        $sales = Deal::query()
            ->whereBetween('created_at', [$start, $end])
            ->get(['created_at', 'amount'])
            ->groupBy(
                fn ($deal) => $deal->created_at->format('Y-m')
            )
            ->map(
                fn ($deals) => $deals->sum('amount')
            );

        $data = [];

        for ($i = 0; $i < 12; $i++) {
            $period = $start->copy()->addMonths($i);

            $key = $period->format('Y-m');

            $data[] = [
                'period' => $key,
                'value' => $sales->get($key, 0),
            ];
        }

        return $data;
    }

    private function getWidgetDefinitions(): array
    {
        return [
            [
                'key' => 'leads',
                'module' => 'leads',
                'label' => 'Leads',
                'filters' => [
                    [
                        'key' => 'all',
                        'label' => 'All Leads',
                    ],
                    [
                        'key' => 'new',
                        'label' => 'New Leads',
                    ],
                ],
                'fields' => [
                    'first_name',
                    'last_name',
                    'company_name',
                    'email',
                    'phone',
                    'source',
                    'status',
                ],
            ],

            [
                'key' => 'contacts',
                'module' => 'contacts',
                'label' => 'Contacts',
                'filters' => [
                    [
                        'key' => 'all',
                        'label' => 'All Contacts',
                    ],
                ],
                'fields' => [
                    'name',
                    'phone',
                    'email',
                    'address',
                    'customer_category',
                ],
            ],

            [
                'key' => 'deals',
                'module' => 'deals',
                'label' => 'Deals',
                'filters' => [
                    [
                        'key' => 'all',
                        'label' => 'All Deals',
                    ],
                    [
                        'key' => 'open',
                        'label' => 'Open Deals',
                    ],
                    [
                        'key' => 'won',
                        'label' => 'Won Deals',
                    ],
                    [
                        'key' => 'lost',
                        'label' => 'Lost Deals',
                    ],
                ],
                'fields' => [
                    'title',
                    'amount',
                    'probability',
                    'status',
                    'expected_close_date',
                ],
            ],

            [
                'key' => 'tickets',
                'module' => 'tickets',
                'label' => 'Tickets',
                'filters' => [
                    [
                        'key' => 'all',
                        'label' => 'All Tickets',
                    ],
                    [
                        'key' => 'open',
                        'label' => 'Open Tickets',
                    ],
                ],
                'fields' => [
                    'subject',
                    'category',
                    'status',
                    'priority',
                    'created_by',
                    'assigned_to',
                ],
            ],

            [
                'key' => 'invoices',
                'module' => 'invoices',
                'label' => 'Invoices',
                'filters' => [
                    [
                        'key' => 'all',
                        'label' => 'All Invoices',
                    ],
                    [
                        'key' => 'paid',
                        'label' => 'Paid Invoices',
                    ],
                ],
                'fields' => [
                    'invoice_number',
                    'issue_date',
                    'due_date',
                    'status',
                    'subtotal',
                    'vat',
                    'grand_total',
                ],
            ],
        ];
    }
}