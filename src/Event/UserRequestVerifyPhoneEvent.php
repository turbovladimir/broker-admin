<?php

namespace App\Event;

use App\Service\Auth\DTO\PhoneFetcherTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class UserRequestVerifyPhoneEvent extends Event
{
    use PhoneFetcherTrait;
    private Request $request;

    public function __construct(Request $request)
    {
        $this->fetchPhone($request);
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}