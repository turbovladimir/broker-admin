<?php

namespace App\Form\DTO;

use App\Entity\SmsQueue;

class StartSendingRequest
{
    const PLACEHOLDER_URL = '{{url}}';

    private array $settings;

    public function __construct(public readonly SmsQueue $queue)
    {
    }

    /**
     * @param string $settings
     */
    public function setSettings(string $settings): void
    {
        $this->settings = json_decode($settings, true);
    }

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }
}
//
//тип редиректа(раунд)
//    - на витрину
//    - на оффер
//
//текст сообщения, обязятельно указать плейсхолдер {{url}} (текстерия)
//
//время отправки
//
//+