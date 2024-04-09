<?php

namespace App\Service\Sms\DTO;

class SendSmsRequest
{
    public function __construct(
        private array $phoneNumbers,
        private string $message
    )
    {
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getPhoneNumbers(): array
    {
        return $this->phoneNumbers;
    }
}