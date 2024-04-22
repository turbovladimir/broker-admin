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

    public function getMethod() : string
    {
        return 'POST';
    }

    public function getUrl() : string
    {
        return 'https://a2p-sms-https.beeline.ru/proto/http';
    }

    public function __toString(): string
    {
        return json_encode([
            'url' => $this->getUrl(),
            'method' => $this->getMethod(),
            'phones' => $this->getPhoneNumbers(),
            'message' => $this->getMessage()
        ], JSON_PRETTY_PRINT);
    }
}