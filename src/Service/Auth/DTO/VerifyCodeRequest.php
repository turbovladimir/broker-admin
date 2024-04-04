<?php

namespace App\Service\Auth\DTO;

use App\Service\Auth\Exception\PhoneVerify\InvalidFormatCodeException;
use Symfony\Component\HttpFoundation\Request;

class VerifyCodeRequest
{
    const CODE_LENGTH = 4;

    private int $code;
    private int $phone;
    private string $sessionId;
    private \DateTime $time;

    /**
     * @return int
     */
    public function getPhone(): int
    {
        return $this->phone;
    }

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
        $code = $request->request->get('code');
        $obj->setCode($code);
        $obj->phone = '777777777';
        $obj->sessionId = $request->getSession()->getId();
        $obj->time = new \DateTime();

        return $obj;
    }

    private function setCode($code): void
    {
        if (!is_numeric($code)) {
            throw new InvalidFormatCodeException();
        }

        $length = ceil(log10(abs($code) + 1));

        if ($length != self::CODE_LENGTH) {
            throw new InvalidFormatCodeException();
        }

        $this->code = $code;
    }
}