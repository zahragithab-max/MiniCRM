<?php

namespace App\Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Dashboard\Services\WidgetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WidgetController extends Controller
{
    public function __construct(
        private WidgetService $widgetService
    ) {
    }

    public function show(Request $request): JsonResponse
    {
        $moduleDefinitions = [
            'contacts' => [
                'filters' => [
                    'all',
                ],
                'fields' => [
                    'name',
                    'phone',
                    'email',
                    'address',
                    'customer_category',
                ],
            ],

            'deals' => [
                'filters' => [
                    'all',
                    'open',
                    'won',
                    'lost',
                ],
                'fields' => [
                    'title',
                    'amount',
                    'probability',
                    'status',
                    'expected_close_date',
                ],
            ],

            'leads' => [
                'filters' => [
                    'all',
                    'new',
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

            'tickets' => [
                'filters' => [
                    'all',
                    'open',
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

            'invoices' => [
                'filters' => [
                    'all',
                    'paid',
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

        $request->validate([
            'module' => [
                'required',
                'string',
                Rule::in(array_keys($moduleDefinitions)),
            ],

            'filter' => [
                'required',
                'string',
            ],

            'fields' => [
                'required',
                'array',
                'min:1',
            ],

            'fields.*' => [
                'required',
                'string',
            ],
        ]);

        $module = $request->input('module');
        $filter = $request->input('filter');
        $fields = $request->input('fields');

        if (
            !in_array(
                $filter,
                $moduleDefinitions[$module]['filters'],
                true
            )
        ) {
            return response()->json([
                'message' => 'Invalid filter for the selected module.',
                'errors' => [
                    'filter' => [
                        'The selected filter is not available for this module.',
                    ],
                ],
            ], 422);
        }

        $invalidFields = array_values(
            array_diff(
                $fields,
                $moduleDefinitions[$module]['fields']
            )
        );

        if (!empty($invalidFields)) {
            return response()->json([
                'message' => 'Invalid fields for the selected module.',
                'errors' => [
                    'fields' => [
                        'The following fields are not available for this module: '
                        . implode(', ', $invalidFields),
                    ],
                ],
            ], 422);
        }

        return $this->success([
            'module' => $module,
            'filter' => $filter,
            'fields' => $fields,
            'data' => $this->widgetService->getData(
                $module,
                $filter,
                $fields
            ),
        ]);
    }
}