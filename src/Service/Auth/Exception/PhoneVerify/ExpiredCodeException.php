<?php

namespace App\Service\Auth\Exception\PhoneVerify;

class ExpiredCodeException extends \Exception implements ClientErrorAwareInterface
{
    public function getClientMessage(): string
    {
        return "Срок действия проверочного кода истек.";
    }
}