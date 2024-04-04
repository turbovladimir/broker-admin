<?php

namespace App\Service\Auth;

use App\Entity\PhoneVerifyJob;
use App\Service\Auth\DTO\SendCodeRequest;
use App\Service\Auth\DTO\VerifyCodeRequest;
use App\Service\Auth\Exception\PhoneVerify\ExpiredCodeException;
use App\Service\Auth\Exception\PhoneVerify\NotFoundCodeException;
use App\Service\Sms\DTO\SendSmsRequest;
use App\Service\Sms\Sender;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PhoneVerifier
{
    public function __construct(
        private Sender $smsSender,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $phoneVerifyLogger
    )
    {
    }

    public function sendCode(SendCodeRequest $request) : void
    {
        try {
            $job = (new PhoneVerifyJob())
                ->setAddedAt(new \DateTime())
                ->setIsActive(true)
                ->setPhone($request->getPhone())
                ->setCode($this->generateCode())
                ->setSessionId($request->getSessionId())
            ;

            $message = sprintf('Yor code: %d', $job->getCode());
            $this->smsSender->send(new SendSmsRequest([$job->getPhone()], $message));
            $this->entityManager->persist($job);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            $this->phoneVerifyLogger->error('Error occurring during send phone', ['exception', $e->getMessage()]);

            throw $e;
        }
    }

    public function verify(VerifyCodeRequest $request) : bool
    {
        /** @var PhoneVerifyJob $job */
        $job = $this->entityManager->getRepository(PhoneVerifyJob::class)->findActiveJob($request);

        if (!$job) {
            throw new NotFoundCodeException();
        }

        if ($request->getTime()->modify('-5 min') > $job->getAddedAt()) {
            throw new ExpiredCodeException();
        }

        $this->entityManager->persist($job->setIsVerified(true));
        $this->entityManager->flush();

        return true;
    }

    private function generateCode() : int
    {
        return random_int(1000, 9999);
    }
}