<?php

namespace App\Form\Exception;

interface ClientErrorAwareInterface
{
    /**
     * @return string
     */
    public function getClientMessage(): string;
}