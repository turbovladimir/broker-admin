<?php

namespace App\Service\Auth\Exception\PhoneVerify;

use App\Form\Exception\ClientErrorAwareInterface;

class ExpiredCodeException extends \Exception implements ClientErrorAwareInterface
{
    public function getClientMessage(): string
    {
        return "Срок действия проверочного кода истек.";
    }
}