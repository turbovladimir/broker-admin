<?php

namespace App\Service\Checker\LeadGid;

use Psr\Http\Message\ResponseInterface;

class ResponseHandler extends \App\Service\Rest\DTO\ResponseHandler
{

    public function parseResponse(): array
    {
        return json_decode((string)$this->response->getBody(), true)['data'];
    }
}