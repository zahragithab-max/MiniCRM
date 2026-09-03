<?php

namespace App\Modules\Accounts\Services;

use App\Modules\Accounts\Models\Account;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountService
{
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Account::latest()->paginate($perPage);
    }

    public function findById(int $id): Account
    {
        return Account::findOrFail($id);
    }

    public function create(array $data): Account
    {
        return Account::create($data);
    }

    public function update(Account $account, array $data): Account
    {
        $account->update($data);

        return $account->fresh();
    }

    public function delete(Account $account): void
    {
        $account->delete();
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $fileName = 'accounts-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID',
                'Name',
                'Customer Category',
                'Created At',
            ]);

            Account::latest()
                ->chunk(500, function ($accounts) use ($handle) {
                    foreach ($accounts as $account) {
                        fputcsv($handle, [
                            $account->id,
                            $account->name,
                            $account->customer_category,
                            $account->created_at?->toDateTimeString(),
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function restore(int $id): Account
    {
        $account = Account::withTrashed()->findOrFail($id);

        $account->restore();

        return $account->fresh();
    }
}