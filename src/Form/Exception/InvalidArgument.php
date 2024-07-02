<?php

namespace App\Form\Exception;

class InvalidArgument extends \Exception implements ClientErrorAwareInterface
{
    public string $argumentName;

    public function getClientMessage(): string
    {
        return  sprintf('Некорректное значение %s', $this->argumentName);
    }
}