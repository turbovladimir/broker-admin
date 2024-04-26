<?php

namespace App\Service\Sms;

use App\Service\Rest\Client;
use App\Service\Rest\Exception\InvalidResponseBodyException;
use GuzzleHttp\RequestOptions;

class Sender
{

    public function __construct(private Client $client, private array $credentals)
    {
    }

    public function send(array $phones, string $message): void
    {
       $response = $this->client->post('https://a2p-sms-https.beeline.ru/proto/http', [RequestOptions::FORM_PARAMS => [
            'action' => 'post_sms',
            'message' => $message,
            'target' => implode(',', $phones),
            'user' => $this->credentals['user'],
            'pass' => $this->credentals['password']
        ]]);

        $body = (string)$response->getBody();

        if (str_contains($body, 'errors')) {
            throw new InvalidResponseBodyException($body);
        }
    }
}