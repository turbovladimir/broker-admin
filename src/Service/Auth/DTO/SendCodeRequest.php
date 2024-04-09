<?php

namespace App\Service\Auth\DTO;

use Symfony\Component\HttpFoundation\Request;

class SendCodeRequest
{
    use PhoneFetcherTrait;
    private string $sessionId;

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
        $obj->fetchPhone($request);
        $obj->sessionId = $request->getSession()->getId();

        return $obj;
    }


}