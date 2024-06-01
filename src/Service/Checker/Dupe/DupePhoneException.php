<?php

namespace App\Service\Checker\Dupe;

use App\Service\Auth\Exception\PhoneVerify\ClientErrorAwareInterface;

class DupePhoneException extends \Exception implements ClientErrorAwareInterface
{

    public function getClientMessage(): string
    {
        return 'Вы уже оставляли заявку! Благодарим вас';
    }
}