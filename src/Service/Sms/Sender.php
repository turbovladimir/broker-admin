<?php

namespace App\Service\Sms;

use App\Service\Sms\DTO\SendSmsRequest;
use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Client\ClientInterface;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;

class Sender
{

    public function __construct(private ClientInterface $client, private LoggerInterface $smsLogger, private array $credentals)
    {
    }

    public function send(SendSmsRequest $request): void
    {
        try {
            $this->client->request($request->getMethod(), $request->getUrl(), [RequestOptions::FORM_PARAMS => [
                'action' => 'post_sms',
                'message' => $request->getMessage(),
                'target' => implode(',', $request->getPhoneNumbers()),
                'user' => $this->credentals['user'],
                'pass' => $this->credentals['password']
            ]]);
        } catch (BadResponseException $exception) {
            $response = $exception->getResponse();
            $this->smsLogger->error('Send verify sms fail.', [
                'request' => $request,
                'response' => [
                    'status' => $response->getStatusCode(),
                    'body' => (string)$response->getBody()
                ]
            ]);
        }
    }
}