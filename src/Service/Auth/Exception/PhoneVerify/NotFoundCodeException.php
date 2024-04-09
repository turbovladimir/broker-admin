<?php

namespace App\Service\Auth\Exception\PhoneVerify;

class NotFoundCodeException extends \Exception implements ClientErrorAwareInterface
{
    public function getClientMessage(): string
    {
        return "Неверный проверочный код.";
    }
}