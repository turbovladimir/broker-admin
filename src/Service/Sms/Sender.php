<?php

namespace App\Service\Sms;

use App\Service\Rest\Client;
use App\Service\Rest\DTO\RequestData;
use App\Service\Sms\DTO\ResponseHandler as SmsSenderResponseHandler;
use App\Service\Sms\DTO\ResponseValidator;
use GuzzleHttp\RequestOptions;

class Sender
{

    public function __construct(private Client $client, private array $credentals)
    {
    }

    public function send(array $phones, string $message): void
    {
        $requestData = new RequestData('POST', 'https://a2p-sms-https.beeline.ru/proto/http',
            [RequestOptions::FORM_PARAMS => [
            'action' => 'post_sms',
            'message' => $message,
            'target' => implode(',', $phones),
            'user' => $this->credentals['user'],
            'pass' => $this->credentals['password']
        ]]);


        $responseHandler = new SmsSenderResponseHandler(new ResponseValidator());
        $this->client->makeAsyncRequest($requestData, $responseHandler);
    }
}