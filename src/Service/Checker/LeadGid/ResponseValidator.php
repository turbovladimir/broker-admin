<?php

namespace App\Service\Checker\LeadGid;

use App\Service\Rest\DTO\ResponseValidatorInterface;
use App\Service\Rest\Exception\InvalidResponseBodyException;
use Psr\Http\Message\ResponseInterface;

class ResponseValidator implements ResponseValidatorInterface
{

    public function validate(ResponseInterface $response): void
    {
        $body = (string)$response->getBody();

        $data = json_decode($body, true);

        if (empty($data) || $data['success'] !== true) {
            throw new InvalidResponseBodyException($body);
        }
    }
}