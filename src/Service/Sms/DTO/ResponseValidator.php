<?php

namespace App\Service\Sms\DTO;

use App\Service\Rest\DTO\ResponseValidatorInterface;
use App\Service\Rest\Exception\InvalidResponseBodyException;
use Psr\Http\Message\ResponseInterface;

class ResponseValidator implements ResponseValidatorInterface
{

    public function validate(ResponseInterface $response): void
    {
        $body = (string)$response->getBody();

        if (str_contains($body, 'errors')) {
            throw new InvalidResponseBodyException($body);
        }
    }
}