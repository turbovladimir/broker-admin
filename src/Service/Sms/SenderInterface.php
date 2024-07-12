<?php

namespace App\Service\Sms;

interface SenderInterface
{
    public function send(array $phones, string $message): void;

}