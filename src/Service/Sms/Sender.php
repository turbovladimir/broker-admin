<?php

namespace App\Service\Sms;

use App\Entity\SendingJobStatus;
use App\Entity\SendingSmsJob;
use App\Service\Rest\Client;
use App\Service\Rest\Exception\InvalidResponseBodyException;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;

class Sender
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
        private EntityManagerInterface $entityManager,
        private LoggerInterface $smsLogger
    ){}

    public function massSending(array $jobs) : void
    {
        foreach ($jobs as $job) {
            $phone = $job->getContact()->getPhone();
            $message = $job->getSms()->getMessage();
            $this->smsLogger->info('Lets do another great job...', ['job' => [
                'id' => $job->getId(),
                'phone' => $phone,
                'message' => $message
            ]]);

            try {
                $this->send([$phone], $message);
            } catch (\Throwable $exception) {
                $this->smsLogger->warning($exception->getMessage());
                $this->entityManager->persist(
                    $job->setStatus(SendingJobStatus::Error)
                    ->setErrorText($exception->getMessage())
                );

                continue;
            }

            $this->smsLogger->info('Sir, job is done!');
            $this->entityManager->persist($job->setStatus(SendingJobStatus::Sent));
        }

        $this->entityManager->flush();
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