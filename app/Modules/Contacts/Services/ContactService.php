<?php

namespace App\Modules\Contacts\Services;

use App\Modules\Contacts\Models\Contact;
use App\Modules\Contacts\Models\ContactEmail;
use App\Modules\Contacts\Models\ContactPhone;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;


class ContactService
{
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Contact::with(['account', 'phones', 'emails'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * @return Contact
     */
    public function findById(int $id): Contact
    {
        /** @var Contact $contact */
        $contact = Contact::with(['account', 'phones', 'emails'])
            ->findOrFail($id);

        return $contact;
    }

    public function create(array $data): Contact
    {
        $phones = $data['phones'] ?? [];
        $emails = $data['emails'] ?? [];

        $this->checkDuplicatePhones($phones);
        $this->checkDuplicateEmails($emails);

        unset($data['phones'], $data['emails']);

        $contact = Contact::create($data);

        $contact->phones()->createMany(
            array_map(
                fn ($phone) => ['phone' => $phone],
                $phones
            )
        );

        $contact->emails()->createMany(
            array_map(
                fn ($email) => ['email' => $email],
                $emails
            )
        );

        return $contact->load(['account', 'phones', 'emails']);
    }

    public function update(Contact $contact, array $data): Contact
    {
        $phones = $data['phones'] ?? null;
        $emails = $data['emails'] ?? null;

        if ($phones !== null) {
            $this->checkDuplicatePhones($phones, $contact->id);
        }

        if ($emails !== null) {
            $this->checkDuplicateEmails($emails, $contact->id);
        }

        unset($data['phones'], $data['emails']);

        $contact->update($data);

        if ($phones !== null) {
            $contact->phones()->delete();

            $contact->phones()->createMany(
                array_map(
                    fn ($phone) => ['phone' => $phone],
                    $phones
                )
            );
        }

        if ($emails !== null) {
            $contact->emails()->delete();

            $contact->emails()->createMany(
                array_map(
                    fn ($email) => ['email' => $email],
                    $emails
                )
            );
        }

        return $contact->fresh(['account', 'phones', 'emails']);
    }

    public function delete(Contact $contact): void
    {
        $contact->delete();
    }

    public function restore(int $id): Contact
    {
        $contact = Contact::withTrashed()->findOrFail($id);

        $contact->restore();

        return $contact->fresh(['account', 'phones', 'emails']);
    }

    private function checkDuplicatePhones(
        array $phones,
        ?int $ignoreContactId = null
    ): void {
        foreach ($phones as $phone) {
            $query = ContactPhone::where('phone', $phone);

            if ($ignoreContactId !== null) {
                $query->where('contact_id', '!=', $ignoreContactId);
            }

            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'phones' => "The phone number {$phone} is already used by another contact.",
                ]);
            }
        }
    }

    private function checkDuplicateEmails(
        array $emails,
        ?int $ignoreContactId = null
    ): void {
        foreach ($emails as $email) {
            $query = ContactEmail::where('email', $email);

            if ($ignoreContactId !== null) {
                $query->where('contact_id', '!=', $ignoreContactId);
            }

            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'emails' => "The email address {$email} is already used by another contact.",
                ]);
            }
        }
    }
}