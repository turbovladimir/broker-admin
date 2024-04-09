<?php

namespace App\Service\Auth\DTO;

use App\Service\Auth\Exception\PhoneVerify\InvalidPhoneNumberException;
use Symfony\Component\HttpFoundation\Request;

trait PhoneFetcherTrait
{
    private ?string $phone = null;

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    protected function fetchPhone(Request $request) : void
    {
        $phone = $request->request->get('phone');

        if (empty($phone)) {
            throw new InvalidPhoneNumberException();
        }

        preg_match('#\+7\((\d{3})\)(\d{3})-(\d{2})-(\d{2})#', $phone, $matches);

        if (empty($matches)) {
            throw new InvalidPhoneNumberException();
        }

        $this->phone = $matches[1] . $matches[2] . $matches[3] . $matches[4];
    }
}