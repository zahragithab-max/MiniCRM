<?php

namespace App\Jobs;

use App\Mail\VerificationCodeMail;
use App\Modules\Settings\Auth\Models\VerificationCode;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendVerificationCodeJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public VerificationCode $verificationCode,
        public string $plainCode
    ) {
    }

    public function handle(): void
    {
        $this->verificationCode->load('user');
    

        Mail::to($this->verificationCode->user->email)
            ->send(
                new VerificationCodeMail(
                    $this->verificationCode,
                    $this->plainCode
                )
            );
    }
}