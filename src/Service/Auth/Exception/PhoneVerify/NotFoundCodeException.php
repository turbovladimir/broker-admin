<?php

namespace App\Service\Auth\Exception\PhoneVerify;

use App\Form\Exception\ClientErrorAwareInterface;

class NotFoundCodeException extends \Exception implements ClientErrorAwareInterface
{
    public function getClientMessage(): string
    {
        return "Неверный проверочный код.";
    }
}