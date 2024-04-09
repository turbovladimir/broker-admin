<?php

namespace App\Service\Auth\DTO;

use App\Service\Auth\Exception\PhoneVerify\InvalidFormatCodeException;
use Symfony\Component\HttpFoundation\Request;

class VerifyCodeRequest
{
    use PhoneFetcherTrait;

    const CODE_LENGTH = 4;

    private int $code;
    private string $sessionId;
    private \DateTime $time;

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime(\DateTime $time): void
    {
        $this->time = $time;
    }

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }
    public static function create(Request $request) : self
    {
        $obj = new self();
        $code = $request->request->all('code');
        $obj->setCode($code);
        $obj->fetchPhone($request);
        $obj->sessionId = $request->getSession()->getId();
        $obj->time = new \DateTime();

        return $obj;
    }

    private function setCode($code): void
    {
        if (!is_array($code) || count($code) < self::CODE_LENGTH) {
            throw new InvalidFormatCodeException();
        }

        $c = '';

        foreach ($code as $d) {
            if (!is_numeric($d)) {
                throw new InvalidFormatCodeException();
            }

            $c .= $d;
        }

        $this->code = (int)$c;
    }
}