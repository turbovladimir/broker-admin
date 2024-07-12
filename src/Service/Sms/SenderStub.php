<?php

namespace App\Service\Sms;

class SenderStub implements SenderInterface
{

    public function send(array $phones, string $message): void
    {
        // TODO: Implement send() method.
    }
}