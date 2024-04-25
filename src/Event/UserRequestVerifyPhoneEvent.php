<?php

namespace App\Event;

use App\Service\Auth\DTO\PhoneFetcherTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UserRequestVerifyPhoneEvent extends Event
{
    use PhoneFetcherTrait;
    private SessionInterface $session;

    public function __construct(Request $request)
    {
        $this->fetchPhone($request);
        $this->session = $request->getSession();
    }

    /**
     * @return SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }
}