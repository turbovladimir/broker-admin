<?php

namespace App\Service\Checker\Dupe;

use App\Form\Exception\ClientErrorAwareInterface;

class DupePhoneException extends \Exception implements ClientErrorAwareInterface
{

    public function getClientMessage(): string
    {
        return 'Вы уже оставляли заявку! Благодарим вас';
    }
}