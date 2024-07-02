<?php

namespace App\Service\Auth\Exception\PhoneVerify;

use App\Form\Exception\ClientErrorAwareInterface;

class InvalidPhoneNumberException extends \Exception implements ClientErrorAwareInterface
{

    public function getClientMessage(): string
    {
        return 'Не удалось определить номер телефона.';
    }
}