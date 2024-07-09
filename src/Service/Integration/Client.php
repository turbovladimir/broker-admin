<?php

namespace App\Service\Integration;

use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class Client extends \GuzzleHttp\Client
{
    public function __construct(private LoggerInterface $restLogger)
    {
        parent::__construct([]);
    }

    public function request(string $method, $uri = '', array $options = []): ResponseInterface
    {
        $this->restLogger->info('Send request',[
            'method' => $method,
            'uri' => $uri,
            'options' => $options
        ]);

        try {
            $resp = parent::request($method, $uri, $options);

            $this->restLogger->info('Get response',[
                'code' => $resp->getStatusCode(),
                'body' => substr((string)$resp->getBody(), 0, 30)
            ]);

            return $resp;
        } catch (BadResponseException $exception) {
            $resp = $exception->getResponse();

            $this->restLogger->error('Get bad response error.',[
                'code' => $resp->getStatusCode(),
                'body' => substr((string)$resp->getBody(), 0, 30)
            ]);

            throw $exception;
        }
    }
}