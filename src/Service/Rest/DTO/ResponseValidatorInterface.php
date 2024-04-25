<?php

namespace App\Service\Rest\DTO;

use App\Service\Rest\Exception\InvalidResponseBodyException;
use Psr\Http\Message\ResponseInterface;

interface ResponseValidatorInterface
{
    /**
     * @param ResponseInterface $response
     * @return void
     *@throws InvalidResponseBodyException
     */
    public function validate(ResponseInterface $response) : void;
}