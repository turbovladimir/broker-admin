<?php

namespace App\Service\Sms;

use App\Service\Integration\Client;
use App\Service\Integration\Exception\InvalidResponseBodyException;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;

class Sender implements SenderInterface
{

    /**
     * @param Client $client
     * @param array<string> $credentals
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $smsLogger
     */
    public function __construct(
        private Client $client,
        private array $credentals,
    ){}

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