<?php

namespace App\Service\Auth\DTO;

use Symfony\Component\HttpFoundation\Request;

class SendCodeRequest
{
    private int $phone;
    private string $sessionId;

    /**
     * @return int
     */
    public function getPhone(): int
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public static function create(Request $request) : self
    {
        $obj = new self();
        $obj->phone = '777777777';
        $obj->sessionId = $request->getSession()->getId();

        return $obj;
    }
}