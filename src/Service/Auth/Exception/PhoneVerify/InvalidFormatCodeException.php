<?php

namespace App\Service\Auth\Exception\PhoneVerify;

class InvalidFormatCodeException extends \Exception implements ClientErrorAwareInterface
{
    public function getClientMessage(): string
    {
        return "Неверный проверочный код.";
    }
}