<?php

namespace App\Service\Auth\Exception\PhoneVerify;

interface ClientErrorAwareInterface
{
    /**
     * @return string
     */
    public function getClientMessage(): string;
}