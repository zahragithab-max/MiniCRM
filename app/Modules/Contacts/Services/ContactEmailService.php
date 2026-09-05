<?php

namespace App\Modules\Contacts\Services;

use App\Modules\Contacts\Mail\ContactEmailMail;
use App\Modules\Contacts\Models\Contact;
use Illuminate\Support\Facades\Mail;

class ContactEmailService
{
    public function send(
        Contact $contact,
        string $subject,
        string $bodyText
    ): void {
        $email = $contact->emails()->first();

        if (!$email) {
            throw new \RuntimeException(
                'This contact does not have an email address.'
            );
        }

        Mail::to($email->email)->send(
            new ContactEmailMail(
                subjectText: $subject,
                bodyText: $bodyText
            )
        );
    }
}