<?php

namespace App\Service\Rest;

use App\Service\Rest\DTO\RequestData;
use App\Service\Rest\DTO\ResponseHandler;
use App\Service\Rest\Exception\InvalidResponseBodyException;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

class Client extends \GuzzleHttp\Client
{
    public function __construct(private LoggerInterface $restLogger)
    {
        parent::__construct([]);
    }

    public function makeAsyncRequest(RequestData $requestData, ResponseHandler $responseHandler)
    {
        $this->restLogger->info('Make http request', [
            'request' => [
                'time' =>  $requestTime = $requestData->getTimestamp(),
                'method' => $requestData->getMethod(),
                'url' => $requestData->getUrl(),
                'options' => $requestData->getOptions(),
            ]
        ]);

        try {
            $promise = $this->requestAsync($requestData->getMethod(), $requestData->getUrl(), $requestData->getOptions());
            $promise->then(
                $responseHandler,
                function (RequestException $exception) use($requestTime) {
                    $this->restLogger->error('Request fail. Request exception occurrence.', [
                        'error' => $exception->getMessage(),
                        'code' => $exception->getCode(),
                        'request_time' => $requestTime
                    ]);

                    throw $exception;
                }
            );
            $promise->wait();
        } catch (InvalidResponseBodyException $exception) {
            $this->restLogger->error('Request fail. Body contain errors.', [
                'error' => $exception->getMessage(),
                'body' => $exception->getBodySubstr(),
                'request_time' => $requestTime
            ]);

            throw $exception;
        }
    }
}