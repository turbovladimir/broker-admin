<?php

namespace App\Service\Sms;

use App\Service\Sms\DTO\SendSmsRequest;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class Sender
{

    public function __construct(private ClientInterface $client, private LoggerInterface $telegramLogger, private array $credentals)
    {
    }

    public function send(SendSmsRequest $request): void
    {
        try {
            $response = $this->client->request($request->getMethod(), $request->getUrl(), [RequestOptions::FORM_PARAMS => [
                'action' => 'post_sms',
                'message' => $request->getMessage(),
                'target' => implode(',', $request->getPhoneNumbers()),
                'user' => $this->credentals['user'],
                'pass' => $this->credentals['password']
            ]]);

            $this->validateResponse($response);
        } catch (BadResponseException $exception) {
            $response = $exception->getResponse();
            $this->telegramLogger->error('Send verify sms fail.', [
                'request' => $request,
                'response' => [
                    'status' => $response->getStatusCode(),
                    'body' => (string)$response->getBody()
                ]
            ]);
        }
    }

    private function validateResponse(ResponseInterface $response) : void
    {
        $body = (string)$response->getBody();

        if (str_contains($body, 'errors')) {
            throw new BadResponseException('Body contains errors', new Request('POST','http://example', []), $response);
        }
    }
}