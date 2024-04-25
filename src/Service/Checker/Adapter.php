<?php

namespace App\Service\Checker;

use App\Service\Checker\LeadGid\ResponseHandler as LeadGidResponseHandler;
use App\Service\Checker\LeadGid\ResponseValidator as LeadGidResponseValidator;
use App\Service\Rest\Client;
use App\Service\Rest\DTO\RequestData;
use App\Service\Rest\DTO\ResponseHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class Adapter
{
    public function __construct(private Client $client
    )
    {
    }

    public function getReport(string $phone) : array
    {
        $handler = new LeadGidResponseHandler(new LeadGidResponseValidator());
        $this->client->makeAsyncRequest(
            new RequestData(
                'POST',
                'https://phone-checker.lead-core.ru/check', [
                RequestOptions::HEADERS =>
                    ['Authorization' => 'e515e56f49e43c0ad99ce5d4290084b1'],
                RequestOptions::JSON => [
                    'async' => false,
                    'phone' => $phone
                ]
            ]), $handler
        );

        return $handler->parseResponse();
    }
}