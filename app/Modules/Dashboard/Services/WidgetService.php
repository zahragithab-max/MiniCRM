<?php

namespace App\Modules\Dashboard\Services;

use App\Modules\Contacts\Models\Contact;
use App\Modules\Deals\Models\Deal;
use App\Modules\Leads\Models\Lead;
use App\Modules\Tickets\Models\Ticket;
use App\Modules\Invoices\Models\Invoice;

class WidgetService
{
    public function getData(
        string $module,
        string $filter,
        array $fields
    ): array {
        if ($module === 'contacts') {
            return $this->getContacts(
                $filter,
                $fields
            );
        }

        if ($module === 'deals') {
            return $this->getDeals(
                $filter,
                $fields
            );
        }

        if ($module === 'leads') {
            return $this->getLeads(
                $filter,
                $fields
            );
        }

        if ($module === 'tickets') {
            return $this->getTickets(
                $filter,
                $fields
            );
        }

        if ($module === 'invoices') {
            return $this->getInvoices(
                $filter,
                $fields
            );
        }

        return [];
    }

    private function getContacts(
        string $filter,
        array $fields
    ): array {
        if ($filter !== 'all') {
            return [];
        }

        $allowedFields = [
            'name',
            'email',
            'phone',
            'birth_date',
            'address',
            'customer_category',
        ];

        $selectedFields = array_values(
            array_intersect($fields, $allowedFields)
        );

        if (empty($selectedFields)) {
            return [];
        }

        $contacts = Contact::query()
            ->with(['emails', 'phones'])
            ->get();

        return $contacts
            ->map(function (Contact $contact) use ($selectedFields) {
                $data = [];

                foreach ($selectedFields as $field) {
                    $data[$field] = match ($field) {
                        'email' => $contact->emails->first()?->email,
                        'phone' => $contact->phones->first()?->phone,
                        default => $contact->{$field} ?? null,
                    };
                }

                return $data;
            })
            ->values()
            ->toArray();
    }

    private function getDeals(
        string $filter,
        array $fields
    ): array {
        $allowedFields = [
            'title',
            'amount',
            'probability',
            'status',
            'expected_close_date',
        ];

        $selectedFields = array_values(
            array_intersect($fields, $allowedFields)
        );

        if (empty($selectedFields)) {
            return [];
        }

        $query = Deal::query();

        if ($filter === 'open') {
            $query->where('status', 'open');
        } elseif ($filter === 'won') {
            $query->where('status', 'won');
        } elseif ($filter === 'lost') {
            $query->where('status', 'lost');
        } elseif ($filter !== 'all') {
            return [];
        }

        $deals = $query->get();

        return $deals
            ->map(function (Deal $deal) use ($selectedFields) {
                $data = [];

                foreach ($selectedFields as $field) {
                    $data[$field] = $deal->{$field} ?? null;
                }

                return $data;
            })
            ->values()
            ->toArray();
    }

    private function getLeads(
        string $filter,
        array $fields
    ): array {
        $allowedFields = [
            'first_name',
            'last_name',
            'company_name',
            'email',
            'phone',
            'source',
            'status',
        ];

        $selectedFields = array_values(
            array_intersect($fields, $allowedFields)
        );

        if (empty($selectedFields)) {
            return [];
        }

        $query = Lead::query();

        if ($filter === 'new') {
            $query->where('status', 'new');
        } elseif ($filter !== 'all') {
            return [];
        }

        $leads = $query->get();

        return $leads
            ->map(function (Lead $lead) use ($selectedFields) {
                $data = [];

                foreach ($selectedFields as $field) {
                    $data[$field] = $lead->{$field} ?? null;
                }

                return $data;
            })
            ->values()
            ->toArray();
    }

    private function getTickets(
        string $filter,
        array $fields
    ): array {
        $allowedFields = [
            'subject',
            'category',
            'status',
            'priority',
            'created_by',
            'assigned_to',
        ];

        $selectedFields = array_values(
            array_intersect($fields, $allowedFields)
        );

        if (empty($selectedFields)) {
            return [];
        }

        $query = Ticket::query();

        if ($filter === 'open') {
            $query->where('status', 'open');
        } elseif ($filter !== 'all') {
            return [];
        }

        $tickets = $query->get();

        return $tickets
            ->map(function (Ticket $ticket) use ($selectedFields) {
                $data = [];

                foreach ($selectedFields as $field) {
                    $data[$field] = $ticket->{$field} ?? null;
                }

                return $data;
            })
            ->values()
            ->toArray();
    }

    private function getInvoices(
        string $filter,
        array $fields
    ): array {
        $allowedFields = [
            'invoice_number',
            'issue_date',
            'due_date',
            'status',
            'subtotal',
            'vat',
            'grand_total',
        ];

        $selectedFields = array_values(
            array_intersect($fields, $allowedFields)
        );

        if (empty($selectedFields)) {
            return [];
        }

        $query = Invoice::query();

        if ($filter === 'paid') {
            $query->where('status', 'paid');
        } elseif ($filter !== 'all') {
            return [];
        }

        $invoices = $query->get();

        return $invoices
            ->map(function (Invoice $invoice) use ($selectedFields) {
                $data = [];

                foreach ($selectedFields as $field) {
                    $data[$field] = $invoice->{$field} ?? null;
                }

                return $data;
            })
            ->values()
            ->toArray();
    }
}